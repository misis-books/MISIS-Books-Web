<?php

namespace Auth\Model;

use Qemy\Core\Application;
use Qemy\Core\Model\AbstractModel;
use Qemy\User\Authorization\Authorization;

class LoginModel extends AbstractModel {

    public function main() {
        $auth = new Authorization($this->getQemyDb(), Application::$request_variables['post']);
        $auth->signIn();
        $this->setData(array(
            'result' => $auth->getResult()
        ));

        return $this;
    }
}