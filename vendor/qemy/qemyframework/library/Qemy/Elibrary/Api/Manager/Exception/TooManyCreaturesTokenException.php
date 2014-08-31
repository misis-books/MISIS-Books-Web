<?php

namespace Qemy\Elibrary\Api\Manager\Exception;

final class TooManyCreaturesTokenException extends ApiException {

    const ERROR_CODE = 4;
    const ERROR_MESSAGE = "Too many created access token";

    function __construct($params) {
        parent::__construct($params, self::ERROR_CODE, self::ERROR_MESSAGE);
    }
}