<?php

namespace Payment\Model;

use Qemy\Core\Application;
use Qemy\Core\Model\AbstractModel;
use Qemy\Payment\Payment;

class AddPayeerPaymentModel extends AbstractModel {

    public function main() {
        $pays = new Payment($this->getQemyDb(), Application::$request_variables['post']);
        $pays->payPayeer();
        if ($pays->isSuccess()) {
            echo $_POST['m_orderid'].'|success';
            Application::stop();
        } else {
            echo $_POST['m_orderid'].'|error';
        }

        return $this;
    }
}