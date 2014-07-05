<?php

namespace CrossdomainMethods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Methods\CrossdomainMethods;

class GetPopularModel extends AbstractModel {

    public function main() {
        $methods = new CrossdomainMethods($this->getRequestParams(), $this->getQemyDb());
        $this->setData($methods->GetPopular());
        return $this;
    }
}