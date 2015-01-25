<?php

namespace Methods\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\Request\RequestManager;
use Qemy\User\Authorization\CheckAuthorization;
use Qemy\User\User;

class GetCategoriesModel extends AbstractModel {

    public function main() {
        $check_auth = new CheckAuthorization($this->getQemyDb());
        $check_auth->check();

        $user = new User($this->getQemyDb(), $check_auth->getUserRow());
        $user->setCheckAuthorization($check_auth);

        $request = new RequestManager($this->getQemyDb(), $user);
        $request->setInitiator($user);

        $this->setData(
            $request->create('getCategories', $this->getRequestParams())
        );

        return $this;
    }
}