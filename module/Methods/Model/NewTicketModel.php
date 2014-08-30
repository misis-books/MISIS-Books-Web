<?php

namespace Methods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\AjaxRequestHandler;
use Qemy\Methods\Methods;

class NewTicketModel extends AbstractModel {

    public function main() {
        $methods = new AjaxRequestHandler($this->getQemyDb());
        $this->setData(
            $methods->addTicket(
                $this->getRequestParams()
            )
        );
        return $this;
    }
}