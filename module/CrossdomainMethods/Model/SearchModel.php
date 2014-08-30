<?php

namespace CrossdomainMethods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\JsonpRequestHandler;
use Qemy\Methods\CrossdomainMethods;

class SearchModel extends AbstractModel {

    public function main() {
        $methods = new JsonpRequestHandler($this->getQemyDb());
        $this->setData(
            $methods->search(
                $this->getRequestParams()
            )
        );
        return $this;
    }
}