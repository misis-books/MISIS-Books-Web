<?php

namespace Qemy\Core\Model;

use Qemy\Core\Application;
use Qemy\Db\QemyDb;

abstract class AbstractModel implements ModelInterface {

    private $qemy_db;
    private $data;
    private $request_params;

    function __construct() {
        $this->qemy_db = new QemyDb(Application::$config['db_options']);
        $this->request_params = Application::$router->getParams();
    }

    protected function getQemyDb() {
        return $this->qemy_db;
    }

    function getData() {
        return $this->data;
    }

    function setData($data) {
        $this->data = $data;
    }

    function getRequestParams() {
        return $this->request_params;
    }
}