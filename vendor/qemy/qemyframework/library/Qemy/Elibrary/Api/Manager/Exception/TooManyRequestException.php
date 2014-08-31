<?php

namespace Qemy\Elibrary\Api\Manager\Exception;

final class TooManyRequestException extends ApiException {

    const ERROR_CODE = 3;
    const ERROR_MESSAGE = "Too many requests. Time to unlock: %d seconds.";

    function __construct($params, $unlock_time) {
        parent::__construct(
            $params,
            self::ERROR_CODE,
            preg_replace("/(%d)/i", $unlock_time - time(), self::ERROR_MESSAGE),
            array('time_to_unlock' => $unlock_time - time())
        );
    }
}