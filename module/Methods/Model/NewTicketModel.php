<?php

namespace Methods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Methods\Methods;

class NewTicketModel extends AbstractModel {

    public function main() {
        $methods = new Methods($this->getRequestParams(), $this->getQemyDb());
        $this->setData($methods->SaveTicket());
        return $this;
    }
}