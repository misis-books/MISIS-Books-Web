<?php

namespace Methods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Methods\Methods;

class AddAuthorModel extends AbstractModel {

    public function main() {
        $methods = new Methods($this->getRequestParams(), $this->getQemyDb());
        $this->setData($methods->AddAuthor());
        return $this;
    }
}