<?php

namespace Qemy\Elibrary\Api\Manager\Exception;

final class MissingAccessTokenException extends ApiException {

    const ERROR_CODE = 1;
    const ERROR_MESSAGE = "Missing access token";

    function __construct($params) {
        parent::__construct($params, self::ERROR_CODE, self::ERROR_MESSAGE);
    }
}