<?php

namespace Methods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\AjaxRequestHandler;

class GetMaterialsModel extends AbstractModel {

    public function main() {
        $methods = new AjaxRequestHandler($this->getQemyDb());
        $this->setData(
            $methods->getMaterials(
                $this->getRequestParams()
            )
        );
        return $this;
    }
}