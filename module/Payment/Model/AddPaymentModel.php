<?php

namespace Payment\Model;

use Qemy\Core\Application;
use Qemy\Core\Model\AbstractModel;
use Qemy\Payment\Payment;

class AddPaymentModel extends AbstractModel {

    public function main() {
        $pays = new Payment($this->getQemyDb(), Application::$request_variables['post']);
        $pays->pay();
        if ($pays->isSuccess()) {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 402 Payment Required');
        }

        return $this;
    }
}