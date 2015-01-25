<?php

namespace Payment\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Payment\Payment;
use Qemy\User\Authorization\CheckAuthorization;
use Qemy\User\User;

class ActivatePromoModel extends AbstractModel {

    public function main() {
        $code = $_COOKIE['promo_code'];

        $check_auth = new CheckAuthorization($this->getQemyDb());
        $check_auth->check();

        $user = new User($this->getQemyDb(), $check_auth->getUserRow());
        $user->setCheckAuthorization($check_auth);
        $result = false;

        $payment = new Payment($this->getQemyDb(), array());

        if (strlen($code) && $payment->hasPromo($code) && $user->isAuth()) {
            $payment->promoActivate($code, $user);
            $result = true;
            setcookie('promo_code', '', 0, '/', 'twosphere.ru');
        }

        $this->setData(array(
            'user' => $user,
            'result' => $result
        ));

        return $this;
    }
}