<?php

namespace Qemy\Elibrary\Request\Exception;

abstract class RequestException extends \Exception {

    protected $message;
    protected $description;
    protected $code;

    private $request_params;

    private $except_fields = array(
        'user'
    );

    function __construct($message, $description, $code) {
        $this->message = $message;
        $this->description = $description;
        $this->code = $code;
        parent::__construct($message, $code);
    }

    protected function setParams($request_params) {
        $this->request_params = $request_params;
    }

    private function createParamsArray() {
        $result = array();
        foreach ($this->request_params as $key => $value) {
            if (in_array($key, $this->except_fields)) continue;
            $result[] = array(
                'key' => $key,
                'value' => $value
            );
        }
        return $result;
    }

    public function getError() {
        $result = array(
            'error' => array(
                'error_code' => $this->code,
                'error_message' => $this->message,
                'error_description' => $this->description,
                'request_params' => $this->createParamsArray()
            )
        );
        return $result;
    }
}