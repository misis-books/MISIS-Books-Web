<?php

namespace Methods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\AjaxRequestHandler;
use Qemy\Methods\Methods;

class GetPopularModel extends AbstractModel {

    public function main() {
        $methods = new AjaxRequestHandler($this->getQemyDb());
        $this->setData(
            $methods->getPopular(
                $this->getRequestParams()
            )
        );
        return $this;
    }
}