<?php

namespace Rockschtar\WordPressTranslationManager\Models;

use Composer\Package\PackageInterface;

class WordPressPackage {

    /**
     * @var PackageInterface
     */
    private $package;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $outputDirectory;


    public static function create(): WordPressPackage {
        return new self();
    }

    /**
     * @return PackageInterface
     */
    public function getPackage(): PackageInterface {
        return $this->package;
    }

    /**
     * @param PackageInterface $package
     * @return WordPressPackage
     */
    public function setPackage(PackageInterface $package): WordPressPackage {
        $this->package = $package;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @param string $type
     * @return WordPressPackage
     */
    public function setType(string $type): WordPressPackage {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return WordPressPackage
     */
    public function setSlug(string $slug): WordPressPackage {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputDirectory(): string {
        return $this->outputDirectory;
    }

    /**
     * @param string $outputDirectory
     * @return WordPressPackage
     */
    public function setOutputDirectory(string $outputDirectory): WordPressPackage {
        $this->outputDirectory = $outputDirectory;
        return $this;
    }


}