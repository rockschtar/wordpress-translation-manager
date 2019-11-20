<?php

namespace Rockschtar\WordPressTranslationManager\Manager;

use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Rockschtar\WordPressTranslationManager\Enums\WordPressPackageType;
use Rockschtar\WordPressTranslationManager\Models\Config;
use Rockschtar\WordPressTranslationManager\Models\HttpResponse;
use Rockschtar\WordPressTranslationManager\Models\WordPressPackage;

class TranslationManager {

    /**
     * @var Config
     */
    private $config;

    /**
     * @var IOInterface
     */
    private $io;

    public function __construct(Config $config, IOInterface $io) {
        $this->config = $config;
        $this->io = $io;
    }

    /**
     * @param PackageInterface[] $packages
     */
    public function updatePackages(array $packages): void {

        foreach ($packages as $package) {

            $this->update($package);
        }

    }

    public function update(PackageInterface $package): void {

        $wordPressPackage = $this->getWordPressPackage($package);

        if ($wordPressPackage !== null) {

            $package = $wordPressPackage->getPackage();
            $version = $package->getPrettyVersion();

            $httpResponse = $this->doHttpRequest("https://api.wordpress.org/translations/plugins/1.0/?version={$version}&slug={$wordPressPackage->getSlug()}");

            if ($httpResponse->getStatus() !== 200) {
                throw new \RuntimeException('Unable to update WordPress translations: Could not connect to api.wordpress.org');
            }

            $translations = json_decode($httpResponse->getResponse(), false, 512, JSON_THROW_ON_ERROR);

            foreach ($translations->translations as $translation) {

                foreach ($this->config->getLanguages() as $language) {

                    $error = '';

                    if ($translation->language === $language) {

                        $tmpFileLocation = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($translation->package);
                        $httpResponse = $this->doHttpRequest($translation->package);
                        $writeResult = file_put_contents($tmpFileLocation, $httpResponse->getResponse());

                        if ($writeResult !== false) {
                            $zipArchive = new \ZipArchive();
                            $resultZipArchiveOpen = $zipArchive->open($tmpFileLocation);

                            if ($resultZipArchiveOpen === true) {
                                $resultZipArchiveExtract = $zipArchive->extractTo($wordPressPackage->getOutputDirectory());
                                if ($resultZipArchiveExtract === false) {
                                    $error = 'Could not extract ZIP';
                                }
                            } else {
                                $error = 'Could not open ZIP. Error: ' . $resultZipArchiveOpen;
                            }
                        } else {
                            $error = 'Could not write ZIP to temp directory';
                        }

                        if ($error !== '') {
                            $this->io->writeError("  - <error>Could not update translation  \"{$language}\" for {$package->getName()}.</error> Error: {$error}");
                        }

                        $this->io->write("  - Updated translation <info>\"{$language}\"</info> for <info>{$package->getName()}</info>");
                    }
                }
            }
        }
    }

    private function getWordPressPackage(PackageInterface $package): ?WordPressPackage {

        $wordpressLanguageDirectory = $this->joinPaths($this->config->getWordpressContentDir(), 'languages');

        if ($package->getType() === 'wordpress-plugin') {
            $slug = str_replace('wpackagist-plugin/', '', $package->getName());
            $type = WordPressPackageType::PLUGIN;
            $outputDirectory = $this->config->getWordpressContentDir() . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'plugins';
        } elseif ($package->getType() === 'wordpress-theme') {
            $slug = str_replace('wpackagist-theme/', '', $package->getName());
            $type = WordPressPackageType::THEME;
            $outputDirectory = $this->joinPaths($wordpressLanguageDirectory, 'themes');
        } elseif ($package->getType() === 'package' && $package->getName() === 'johnpbloch/wordpress') {
            $slug = 'wordpress-core';
            $type = WordPressPackageType::CORE;
            $outputDirectory = $wordpressLanguageDirectory;
        } else {
            return null;
        }

        $wordpressPackage = WordPressPackage::create();
        $wordpressPackage->setPackage($package);
        $wordpressPackage->setSlug($slug);
        $wordpressPackage->setOutputDirectory($outputDirectory);
        $wordpressPackage->setType($type);

        return $wordpressPackage;
    }

    private function joinPaths(...$args) {
        $paths = array();

        foreach ($args as $arg) {
            if ($arg !== '') {
                $paths[] = $arg;
            }
        }

        return preg_replace('#/+#', '/', implode('/', $paths));
    }

    private function doHttpRequest(string $url): HttpResponse {
        $response = file_get_contents($url);
        preg_match('{HTTP\/\S*\s(\d{3})}', $http_response_header[0], $match);
        $httpStatus = (int)$match[1];

        return new HttpResponse($httpStatus, $response);
    }

    public function remove(PackageInterface $package): void {

        $wordPressPackage = $this->getWordPressPackage($package);

        if ($wordPressPackage !== null) {


            $pattern = $this->joinPaths($wordPressPackage->getOutputDirectory(), $wordPressPackage->getSlug() . '*');

            foreach (glob($pattern) as $filename) {
                if (file_exists($filename)) {
                    @unlink($filename);
                }
            }

            $this->io->write("  - Deleted translations for <info>{$wordPressPackage->getSlug()}</info>");

        }
    }

}