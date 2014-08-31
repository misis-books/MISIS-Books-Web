<?php

namespace Qemy\Elibrary\Api\Manager\Exception;

final class InvalidAccessTokenException extends ApiException {

    const ERROR_CODE = 2;
    const ERROR_MESSAGE = "Invalid access token";

    function __construct($params) {
        parent::__construct($params, self::ERROR_CODE, self::ERROR_MESSAGE);
    }
}