<?php

namespace Qemy\User\Authorization;

use Qemy\User\Authorization\Model\AuthorizationModel;
use Qemy\User\User;

class Authorization implements AuthorizationInterface {

    private $db;
    private $data;

    private $result = false;

    /**
     * @param $db \Qemy\Db\QemyDatabase
     * @param $data
     */
    function __construct(&$db, $data = array()) {
        $this->db = $db;
        $this->data = $data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function signIn() {
        $auth_model = new AuthorizationModel($this->db);

        $user_object = array(
            'domain' => empty($this->data['domain']) ? 'id'.$this->data['id'] : $this->data['domain'],
            'first_name' => empty($this->data['first_name']) ? ' ' : $this->data['first_name'],
            'href' => empty($this->data['href']) ? 'http://vk.com' : $this->data['href'],
            'id' => $this->data['id'],
            'last_name' => empty($this->data['last_name']) ? ' ' : $this->data['last_name'],
            'photo' => empty($this->data['photo']) ? ' ' : $this->data['photo']
        );
        foreach ($user_object as $value) {
            if (empty($value)) return;
        }
        $auth_model->setUserObject($user_object);

        $auth_model->auth();

        $result = $auth_model->getResult();
        if ($result) {
            $user = new User($this->db);
            $user->allocateUserByVkId(intval($user_object['id']));

            $key_manager = new KeyManager();
            $access_key = $this->generateKey();
            $key = $key_manager->createKey($user->getId(), $access_key);

            $user->addAccessKey($access_key);
            $this->setCookie('ts_sid', $key, time() + 365 * 24 * 3600, '/', 'twosphere.ru');
            $this->setSession('ts_sid', $key);
        }
        $this->result = $result;
    }

    private function generateKey() {
        return md5(mktime().rand(1, 10e8));
    }

    private function setSession($key, $value) {
        $_SESSION[$key] = $value;
    }

    private function setCookie($name, $value, $remaining_time, $path) {
        setcookie($name, $value, $remaining_time, $path);
    }

    /**
     * @return bool
     */
    public function getResult() {
        return $this->result;
    }
}