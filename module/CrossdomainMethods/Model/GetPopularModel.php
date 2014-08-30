<?php

namespace CrossdomainMethods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\JsonpRequestHandler;
use Qemy\Methods\CrossdomainMethods;

class GetPopularModel extends AbstractModel {

    public function main() {
        $methods = new JsonpRequestHandler($this->getQemyDb());
        $this->setData(
            $methods->getPopular(
                $this->getRequestParams()
            )
        );
        return $this;
    }
}