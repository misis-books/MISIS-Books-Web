<?php

namespace Qemy\Elibrary\Api\Exception;

use Qemy\Elibrary\Request\Exception\RequestException;

class TooManyCreaturesTokenException extends RequestException {

    const ERROR_MESSAGE = 'Too many requests to creation token';
    const ERROR_DESCRIPTION = 'Wait for 5 minutes';
    const ERROR_CODE = 7;

    function __construct($params) {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_DESCRIPTION, self::ERROR_CODE);
        $this->setParams($params);
    }
}