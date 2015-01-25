<?php

namespace Index\Model;

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

        $users = new Users($this->getQemyDb());
        $users_list = $users->getUsersWithSubscription();

        $pashk = $users_list['20'];
        unset($users_list['20']);
        shuffle($users_list);
        $users_list = array_merge($users_list, $users_list);
        array_push($users_list, $pashk);

        $this->setData(array(
            'user' => $user,
            'users' => $users_list
        ));

        return $this;
    }
}