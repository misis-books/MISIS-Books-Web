<?php

namespace Api\Model;

use Qemy\Core\Application;
use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\Api\Api;

class SearchModel extends AbstractModel {

    public function main() {
        $api = new Api($this->getQemyDb());
        $this->setData(
            $api->search(
                Application::$request_variables['request']
            )
        );
        return $this;
    }
}