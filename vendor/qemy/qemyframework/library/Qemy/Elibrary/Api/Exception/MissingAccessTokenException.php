<?php

namespace Qemy\Elibrary\Api\Exception;

use Qemy\Elibrary\Request\Exception\RequestException;

class MissingAccessTokenException extends RequestException {

    const ERROR_MESSAGE = 'Missing Access Token';
    const ERROR_DESCRIPTION = 'User authorization failed: no access_token passed.';
    const ERROR_CODE = 5;

    function __construct($params) {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_DESCRIPTION, self::ERROR_CODE);
        $this->setParams($params);
    }
}