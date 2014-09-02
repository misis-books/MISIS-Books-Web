<?php

namespace Methods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\AjaxRequestHandler;

class AddAuthorModel extends AbstractModel {

    public function main() {
        $methods = new AjaxRequestHandler($this->getQemyDb());
        $this->setData(
            $methods->addAuthor(
                $this->getRequestParams()
            )
        );
        return $this;
    }
}