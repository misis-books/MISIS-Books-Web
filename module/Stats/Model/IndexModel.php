<?php

namespace Stats\Model;

use Qemy\Core\Application;
use Qemy\Core\Model\AbstractModel;
use Qemy\User\Authorization\CheckAuthorization;
use Qemy\User\User;
use Qemy\User\Users;

class IndexModel extends AbstractModel {

    public function main() {
        $check_auth = new CheckAuthorization($this->getQemyDb());
        $check_auth->check();

        $user = new User($this->getQemyDb(), $check_auth->getUserRow());
        $user->setCheckAuthorization($check_auth);

        if ($user->getId() != 1 && $user->getId() != 15) {
            Application::toRoute("/");
            Application::stop();
        }

        $sort = Application::$request_variables['get']['sort'];
        $sort_type = Application::$request_variables['get']['sort_type'];

        $users = new Users($this->getQemyDb());
        $sub_users = $users->getUsersWithSubscriptionNormal($sort, $sort_type);

        $this->setData(array(
            'user' => $user,
            'sub_users' => $sub_users
        ));

        return $this;
    }
}