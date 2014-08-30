<?php

namespace Qemy\Elibrary;

final class JsonpRequestHandler extends RequestHandler {

    function __construct(&$db) {
        parent::__construct($db);
        $this->request_type = self::REQUEST_GET;
    }
}