<?php

namespace Qemy\Elibrary\Api\Exception;

use Qemy\Elibrary\Request\Exception\RequestException;

class TooManyRequestException extends RequestException {

    const ERROR_MESSAGE = 'Too many requests per second';
    const ERROR_DESCRIPTION = 'To call next method you need to wait some time.';
    const ERROR_CODE = 3;

    function __construct($params, $unlock_time) {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_DESCRIPTION, self::ERROR_CODE);
        $this->setParams($params);
    }
}