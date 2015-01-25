<?php

namespace YandexTabloid\Model;

use Qemy\Core\Model\AbstractModel;

class IndexModel extends AbstractModel {

    public function main() {
        $res_today = $this->getQemyDb()->query("SELECT COUNT(*) FROM `users` WHERE recent_activity_time > ?i", time() - 24 * 60 * 60)->fetch_array();
        $today_users = $res_today[0];
        $res_sub = $this->getQemyDb()->query("SELECT COUNT(*) FROM `users` WHERE end_subscription_time > ?i", time())->fetch_array();
        $sub = $res_sub[0];
        $res_users = $this->getQemyDb()->query("SELECT COUNT(*) FROM `users`")->fetch_array();
        $users = $res_users[0];
        $container = array(
            "today" => $today_users,
            "sub" => $sub,
            "users" => $users
        );
        $this->setData($container);
        return $this;
    }
}