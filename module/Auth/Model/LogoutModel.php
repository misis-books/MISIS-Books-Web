<?php

namespace Auth\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\User\Authorization\CheckAuthorization;
use Qemy\User\Authorization\Logout;
use Qemy\User\User;

class LogoutModel extends AbstractModel {

    public function main() {
        $check_auth = new CheckAuthorization($this->getQemyDb());
        $check_auth->check();

        $user = new User($this->getQemyDb(), $check_auth->getUserRow());
        $user->setCheckAuthorization($check_auth);

        $auth = new Logout($this->getQemyDb());
        $auth->logout($user);

        $this->setData(array(
            'result' => $auth->getResult()
        ));

        return $this;
    }
}