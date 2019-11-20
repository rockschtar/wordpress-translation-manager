<?php


namespace Rockschtar\WordPressTranslationManager;


use Composer\Package\RootPackageInterface;
use Rockschtar\WordPressTranslationManager\Models\Config;

class PluginConfig {

    public static function getConfig(RootPackageInterface $package): Config {
        $extra = $package->getExtra();
        $config = Config::create();

        if (array_key_exists('wordpress-translation-manager', $extra)) {

            if (array_key_exists('languages', $extra['wordpress-translation-manager'])) {
                $config->setLanguages($extra['wordpress-translation-manager']['languages']);
            }

            if (array_key_exists('wordpress-content-directory', $extra['wordpress-translation-manager'])) {
                $config->setWordpressContentDir($extra['wordpress-translation-manager']['wordpress-content-directory']);
            }
        }

        return $config;

    }

}