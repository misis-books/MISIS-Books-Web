<?php

namespace Qemy\Elibrary\Api;

use Qemy\Elibrary\Api\Manager\Manager;

final class Api implements ApiInterface {

    private $db;

    function __construct(&$db) {
        $this->db = $db;
    }

    public function search($params) {
        $params = array(
            'q' => (!empty($params['q'])) ? $params['q'] : null,
            'count' => (!empty($params['count'])) ? $params['count'] : null,
            'offset' => (!empty($params['offset'])) ? $params['offset'] : null,
            'category' => (!empty($params['category'])) ? $params['category'] : null,
            'access_token' => (!empty($params['access_token'])) ? $params['access_token'] : null
        );
        return $this->run($params, 'search');
    }

    public function getPopular($params) {
        $params = array(
            'count' => (!empty($params['count'])) ? $params['count'] : null,
            'offset' => (!empty($params['offset'])) ? $params['offset'] : null,
            'category' => (!empty($params['category'])) ? $params['category'] : null,
            'access_token' => (!empty($params['access_token'])) ? $params['access_token'] : null
        );
        return $this->run($params, 'getPopular');
    }

    public function getCategories($params) {
        $params = array(
            'access_token' => (!empty($params['access_token'])) ? $params['access_token'] : null
        );
        return $this->run($params, 'getCategories');
    }

    private function run($params, $method) {
        $manager = new Manager($this->db);
        $manager->setParams($params);
        $manager->setMethod($method);
        return $manager->getMethodResult();
    }

    public function createAccessToken() {
        $manager = new Manager($this->db);
        $manager->setParams(array('ip' => $_SERVER['REMOTE_ADDR']));
        return $manager->createAccessToken();
    }
}