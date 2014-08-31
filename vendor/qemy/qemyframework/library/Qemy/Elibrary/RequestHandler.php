<?php

namespace Qemy\Elibrary;

use Qemy\Elibrary\Methods\Methods;

abstract class RequestHandler {

    private $db;

    const REQUEST_GET = 'get';
    const REQUEST_POST = 'post';

    protected $request_type = self::REQUEST_POST;

    function __construct(&$db) {
        $this->db = $db;
    }

    public function search($params) {
        $methods = new Methods($this->db);
        $params = $params[$this->request_type];
        $result = $methods->search($params);
        $result['hash'] = $params['hash'];
        return $result;
    }

    public function getPopular($params) {
        $methods = new Methods($this->db);
        $params = $params[$this->request_type];
        return $methods->getPopular($params);
    }

    public function addAuthor($params) {
        $methods = new Methods($this->db);
        $params = $params[$this->request_type];
        return $methods->addAuthor($params);
    }

    public function addEdition($params) {
        $methods = new Methods($this->db);
        $params = $params[$this->request_type];
        return $methods->addEdition($params);
    }

    public function addTicket($params) {
        $methods = new Methods($this->db);
        $params = $params[$this->request_type];
        return $methods->addTicket($params);
    }
}