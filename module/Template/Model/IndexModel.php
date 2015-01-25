<?php

namespace Template\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\User\Authorization\CheckAuthorization;
use Qemy\User\User;

class IndexModel extends AbstractModel {

    public function main() {
        $check_auth = new CheckAuthorization($this->getQemyDb());
        $check_auth->check();

        $user = new User($this->getQemyDb(), $check_auth->getUserRow());
        $user->setCheckAuthorization($check_auth);

        $keys = array();
        if ($user->getId() == 1) {
            $res = $this->getQemyDb()->query("SELECT code FROM promo_codes WHERE activate = ?i", 0);
            while ($row = $res->fetch_array(MYSQL_ASSOC)) {
                $keys[] = $row['code'];
            }
        }
        $this->setData(array(
            'keys' => $keys
        ));

        return $this;
    }
}