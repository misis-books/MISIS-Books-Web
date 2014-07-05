<?php

namespace Api\Model;

use Qemy\Api\Api;
use Qemy\Core\Application;
use Qemy\Core\Model\AbstractModel;

class SearchModel extends AbstractModel {

    public function main() {
        $api = new Api(Application::$request_variables, $this->getQemyDb());
        $this->setData($api->Search());
        return $this;
    }
}