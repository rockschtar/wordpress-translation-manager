<?php

namespace Rockschtar\WordPressTranslationManager\Models;

class Result {

    public const SUCCESS = 'success';
    public const ERROR = 'error';

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $message;

    /**
     * Result constructor.
     * @param string $status
     * @param string $message
     */
    private function __construct(string $status, string $message) {
        $this->status = $status;
        $this->message = $message;
    }

    public static function success(string $message): Result {
        return new self(self::SUCCESS, $message);
    }

    public static function error(string $message): Result {
        return new self(self::ERROR, $message);
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }


}