<?php

namespace Doc\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\Elibrary;

class IndexModel extends AbstractModel {

    public function main() {
        $elib = new Elibrary($this->getQemyDb());
        $elib->ShowFile($this->getRequestParams()['get']['id']);
        return $this;
    }
}