<?php

namespace Qemy\Elibrary\Api\Manager\Exception;

abstract class ApiException extends \Exception {

    private $params, $extend_error;

    function __construct($params = array(), $error_code = -1, $error_message = "", $extend_error = array()) {
        $this->params = $params;
        $this->extend_error = $extend_error;
        parent::__construct($error_message, $error_code);
    }

    public function getErrorResult() {
        $result = array(
            "error" => array(
                "error_code" => $this->getCode(),
                "error_msg" => $this->getMessage(),
                "request_params" => $this->getRequestParams()
            )
        );
        foreach ($this->extend_error as $key => $value) {
            $result['error'][$key] = $value;
        }
        return $result;
    }

    private function getRequestParams() {
        $request_params = array();
        foreach ($this->params as $key => $value) {
            $request_params[] = array(
                'key' => $key,
                'value' => $value
            );
        }
        return $request_params;
    }
}