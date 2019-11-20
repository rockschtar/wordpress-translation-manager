<?php

namespace Rockschtar\WordPressTranslationManager\Models;

class HttpResponse {

    /**
     * @var int
     */
    private $status;

    /**
     * @var mixed
     */
    private $response;

    /**
     * HTTPResponse constructor.
     * @param int $status
     * @param mixed $response
     */
    public function __construct(int $status, $response) {
        $this->status = $status;
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getStatus(): int {
        return $this->status;
    }

    /**
     * @param int $status
     * @return HttpResponse
     */
    public function setStatus(int $status): HttpResponse {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * @param mixed $response
     * @return HttpResponse
     */
    public function setResponse($response) {
        $this->response = $response;
        return $this;
    }


}