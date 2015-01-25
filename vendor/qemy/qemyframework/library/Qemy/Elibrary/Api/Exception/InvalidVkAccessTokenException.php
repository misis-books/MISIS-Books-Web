<?php

namespace Qemy\Elibrary\Api\Exception;

use Qemy\Elibrary\Request\Exception\RequestException;

class InvalidVkAccessTokenException extends RequestException {

    const ERROR_MESSAGE = 'Invalid VK Access Token';
    const ERROR_DESCRIPTION = 'Transfered not exist or incorrect VK access token';
    const ERROR_CODE = 6;

    function __construct($params) {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_DESCRIPTION, self::ERROR_CODE);
        $this->setParams($params);
    }
}