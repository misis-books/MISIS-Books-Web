<?php

namespace Qemy\Elibrary\Request\Exception;

class NoSubscriptionException extends RequestException {

    const ERROR_MESSAGE = 'The user has no subscription';
    const ERROR_DESCRIPTION = 'To call this method you need to get the subscription';
    const ERROR_CODE = 2;

    function __construct($params) {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_DESCRIPTION, self::ERROR_CODE);
        $this->setParams($params);
    }
}