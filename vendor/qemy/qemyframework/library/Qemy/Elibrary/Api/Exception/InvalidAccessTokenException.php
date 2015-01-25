<?php

namespace Qemy\Elibrary\Api\Exception;

use Qemy\Elibrary\Request\Exception\RequestException;

class InvalidAccessTokenException extends RequestException {

    const ERROR_MESSAGE = 'Invalid Access Token';
    const ERROR_DESCRIPTION = 'This token doesn\'t exist';
    const ERROR_CODE = 4;

    function __construct($params) {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_DESCRIPTION, self::ERROR_CODE);
        $this->setParams($params);
    }
}