<?php

namespace Methods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\AjaxRequestHandler;

class AddEditionModel extends AbstractModel {

    public function main() {
        $methods = new AjaxRequestHandler($this->getQemyDb());
        $this->setData(
            $methods->addEdition(
                $this->getRequestParams()
            )
        );
        return $this;
    }
}