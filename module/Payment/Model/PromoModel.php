<?php

namespace Payment\Model;

use Qemy\Core\Application;
use Qemy\Core\Model\AbstractModel;
use Qemy\Payment\Payment;

class PromoModel extends AbstractModel {

    public function main() {
        $code = Application::$request_variables['get']['code'];

        $payment = new Payment($this->getQemyDb(), array());
        if (!empty($code) && $payment->hasPromo($code)) {
            setcookie('promo_code', $code, time() + 24 * 60 * 60 * 100, '/', 'twosphere.ru');
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/payment/activatePromo');
        } else {
            echo 'Неверный промо код';
        }

        return $this;
    }
}