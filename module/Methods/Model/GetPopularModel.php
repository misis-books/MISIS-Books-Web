<?php

namespace Methods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Methods\Methods;

class GetPopularModel extends AbstractModel {

    public function main() {
        $methods = new Methods($this->getRequestParams(), $this->getQemyDb());
        $this->setData($methods->GetPopular());
        return $this;
    }
}