<?php

namespace Qemy\Elibrary\Request\Exception;

class NotAuthorizedUserException extends RequestException {

    const ERROR_MESSAGE = 'User not authorized';
    const ERROR_DESCRIPTION = 'To call this method requires authentication';
    const ERROR_CODE = 1;

    function __construct($params) {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_DESCRIPTION, self::ERROR_CODE);
        $this->setParams($params);
    }
}