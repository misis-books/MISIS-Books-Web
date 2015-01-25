<?php

namespace Qemy\User\Authorization;

use Qemy\Core\Application;
use Qemy\Db\QemyDatabase;
use Qemy\User\User;

class CheckAuthorization implements CheckAuthorizationInterface {

    private $db;
    private $user_row;
    private $result = false;

    /**
     * @param $db QemyDatabase
     * @param $data array
     */
    function __construct($db) {
        $this->db = $db;
    }

    public function check() {
        $key = !empty($_SESSION['ts_sid']) ? $_SESSION['ts_sid'] : !1;
        if (!$key) {
            $cookies = Application::$request_variables['cookie'];
            $cookie_key = !empty($cookies['ts_sid']) ? $cookies['ts_sid'] : !1;
            if ($cookie_key) {
                $key_manager = new KeyManager();
                list($user_id, $access_key) = $key_manager->getPair($cookie_key);
                if (!is_numeric($user_id)) {
                    $this->removeCookie('ts_sid');
                    return;
                }
                $user = new User($this->db);
                $user->allocateUserById($user_id);
                if (!$user->accessKeyExists($access_key)) {
                    $this->removeCookie('ts_sid');
                    return;
                }
                $this->setSession('ts_sid', $cookie_key);
                $this->user_row = $user->getObject();
                $this->result = !$user->isEmpty();
            }
        } else {
            $key_manager = new KeyManager();
            $user_id = $key_manager->getPair($key)[0];

            $user = new User($this->db);
            $user->allocateUserById($user_id);

            $this->user_row = $user->getObject();
            $this->result = !$user->isEmpty();
        }
        if ($this->result) {
            $user = new User($this->db, $this->user_row);
            if ($user->hasSubscription()) {
                setcookie('hs_sid', $this->generateCode(), time() + 365 * 24 * 3600, '/', 'twosphere.ru');
            } else {
                if (isset($_COOKIE['hs_sid'])) {
                    setcookie('hs_sid', '', 0, '/', 'twosphere.ru');
                }
            }
        }
    }

    private function setSession($key, $value) {
        $_SESSION[$key] = $value;
    }

    private function removeCookie($name) {
        setcookie($name, '', 0, "/");
    }

    public function getResult() {
        return $this->result;
    }

    public function getUserRow() {
        return $this->result ? $this->user_row : null;
    }

    private function generateCode() {
        return md5(mktime().rand(1, 10e8));
    }
}