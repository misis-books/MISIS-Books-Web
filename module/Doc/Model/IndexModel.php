<?php

namespace Doc\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\Elibrary;
use Qemy\User\Authorization\CheckAuthorization;
use Qemy\User\User;

class IndexModel extends AbstractModel {

    public function main() {
        $check_auth = new CheckAuthorization($this->getQemyDb());
        $check_auth->check();

        $user = new User($this->getQemyDb(), $check_auth->getUserRow());
        $user->setCheckAuthorization($check_auth);

        $elib = new Elibrary($this->getQemyDb(), $user);
        $data = $this->getRequestParams();

        $elib->ShowFile($data['get']['id']);
        return $this;
    }
}