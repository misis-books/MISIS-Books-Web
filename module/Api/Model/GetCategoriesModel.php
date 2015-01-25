<?php

namespace Api\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\Api\ApiManager;

class GetCategoriesModel extends AbstractModel {

    public function main() {
        $api = new ApiManager($this->getQemyDb());

        $this->setData(
            $api->create('getCategories', $this->getRequestParams())
        );

        return $this;
    }
}