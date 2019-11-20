<?php

namespace Rockschtar\WordPressTranslationManager\Models;

class Config {

    private $languages = [];

    private $wordpressContentDir = '';

    public static function create(): Config {
        return new self();
    }

    /**
     * @return array
     */
    public function getLanguages(): array {
        return $this->languages;
    }

    /**
     * @param array $languages
     * @return Config
     */
    public function setLanguages(array $languages): Config {
        $this->languages = $languages;
        return $this;
    }

    /**
     * @return string
     */
    public function getWordpressContentDir(): string {
        return $this->wordpressContentDir;
    }

    /**
     * @param string $wordpressContentDir
     * @return Config
     */
    public function setWordpressContentDir(string $wordpressContentDir): Config {
        $this->wordpressContentDir = $wordpressContentDir;
        return $this;
    }

}