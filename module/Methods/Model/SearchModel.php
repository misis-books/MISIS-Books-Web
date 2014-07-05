<?php

namespace Methods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Methods\Methods;

class SearchModel extends AbstractModel {

    public function main() {
        $methods = new Methods($this->getRequestParams(), $this->getQemyDb());
        $this->setData($methods->Search());
        return $this;
    }
}