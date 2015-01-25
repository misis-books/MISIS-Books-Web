<?php

namespace Qemy\User\Authorization;

use Qemy\User\User;

class Logout {

    private $db;

    private $result = false;

    /**
     * @param $db \Qemy\Db\QemyDatabase
     */
    function __construct(&$db) {
        $this->db = $db;
    }

    /**
     * @param $user User
     */
    public function logout($user) {
        $key_manager = new KeyManager();
        list($user_id, $access_key) = $key_manager->getPair($_SESSION['ts_sid']);

        $this->removeAccessKey($user, $access_key);
        $this->removeSession('ts_sid');
        $this->removeCookie('ts_sid');
        $this->result = true;
    }

    /**
     * @param $user User
     * @param $target_key string
     */
    private function removeAccessKey($user, $target_key) {
        if (!$user->isAuth()) {
            return;
        }
        $user->deleteAccessKey($target_key);
    }

    private function removeCookie($name) {
        setcookie($name, '', 0, '/');
    }

    private function removeSession($key) {
        unset($_SESSION[$key]);
    }

    public function getResult() {
        return $this->result;
    }
}