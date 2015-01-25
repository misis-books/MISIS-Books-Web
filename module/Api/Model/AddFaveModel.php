<?php

namespace Api\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\Api\ApiManager;

class AddFaveModel extends AbstractModel {

    public function main() {
        $api = new ApiManager($this->getQemyDb());

        $this->setData(
            $api->create('addFave', $this->getRequestParams())
        );

        return $this;
    }
}