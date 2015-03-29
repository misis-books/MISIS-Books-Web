<?php

namespace Dev\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\User\Authorization\CheckAuthorization;
use Qemy\User\User;

class IndexModel extends AbstractModel {

    public function main() {
        $check_auth = new CheckAuthorization($this->getQemyDb());
        $check_auth->check();

        $user = new User($this->getQemyDb(), $check_auth->getUserRow());
        $user->setCheckAuthorization($check_auth);

        $this->setData(array(
            'user' => $user
        ));

        return $this;
    }
}