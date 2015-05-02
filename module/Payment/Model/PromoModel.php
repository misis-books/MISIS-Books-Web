<?php

namespace Payment\Model;

use Qemy\Core\Application;
use Qemy\Core\Model\AbstractModel;
use Qemy\Payment\Payment;
use Qemy\User\Authorization\CheckAuthorization;
use Qemy\User\User;

class PromoModel extends AbstractModel {

    public function main() {
        $code = Application::$request_variables['get']['code'];

        $check_auth = new CheckAuthorization($this->getQemyDb());
        $check_auth->check();

        $user = new User($this->getQemyDb(), $check_auth->getUserRow());
        $user->setCheckAuthorization($check_auth);

        $result = false;
        $payment = new Payment($this->getQemyDb(), array());
        if (!empty($code) && $payment->hasPromo($code)) {
            if (!isset($_GET['lock'])) {
                setcookie('promo_code', $code, time() + 24 * 60 * 60 * 100, '/', 'twosphere.ru');
                $result = true;
            }
        }

        $this->setData(array(
            'result' => $result,
            'user' => $user
        ));

        return $this;
    }
}