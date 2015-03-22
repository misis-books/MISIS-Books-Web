<?php

namespace Qemy\User\Authorization\Model;

use Qemy\Db\QemyDatabase;
use Qemy\Elibrary\Api\Exception\TooManyCreaturesTokenException;
use Qemy\User\User;
use Qemy\User\Users;
use Qemy\Vk\Api\VkApi;
use Qemy\Vk\Api\VkApiException;

class AuthorizationModel extends AbstractAuthorizationModel implements AuthorizationModelInterface {

    private $db;

    private $user_object;
    private $auth_params;
    private $vk_object;

    private $api_flag = false;

    private $result = false;

    /**
     * @param $db QemyDatabase
     */
    function __construct(&$db) {
        $this->db = $db;
        parent::__construct($db);
    }

    public function setUserObject($user_object) {
        $this->user_object = $user_object;
    }

    public function setAuthParams($params) {
        $this->auth_params = $params;
    }

    public function toggleApi() {
        $this->api_flag = !$this->api_flag;
    }

    public function auth() {
        if (!$this->api_flag) {
            if ($this->isValidAuth()) {
                $users_manager = new Users($this->db);
                $exists = $users_manager->isVkUserExists(intval($this->user_object['id']));

                $user = $users_manager->getUser();
                if ($user && !$user->isEmpty()) {
                    if ($user->getPhoto() != $this->user_object['photo']) {
                        $user->setPhoto($this->user_object['photo']);
                    }
                    $user->updateLastLoggedTime();
                }

                $result = false;
                if (!$exists) {
                    $result = $users_manager->createUser($this->user_object);
                }
                $this->result = $exists || $result;
            }
        } else {
            $access_token = $this->auth_params['access_token'];
            if (!$access_token) {
                return;
            }
            try {
                $vk_api = new VkApi(self::APP_ID, self::APP_SECRET_KEY, $access_token);
                if (!$vk_api->isAuth()) {
                    return;
                }
                $user_object = $vk_api->api('users.get', array(
                    'fields' => 'domain,photo_200,photo_max'
                ));
                if (isset($user_object['error'])) {
                    throw new TooManyCreaturesTokenException(array(
                        $access_token
                    ));
                }
                $user_object = $user_object['response'][0];
                $user_object['id'] = $user_object['uid'];
                $user_object['photo'] = isset($user_object['photo_200'])
                    ? $user_object['photo_200'] : $user_object['photo_max'];

                if (empty($user_object)) {
                    return;
                }
                $this->vk_object = $user_object;

                $users_manager = new Users($this->db);
                $exists = $users_manager->isVkUserExists(intval($user_object['id']));

                $user = $users_manager->getUser();
                if ($user && !$user->isEmpty()) {
                    if ($user->getPhoto() != $user_object['photo']) {
                        $user->setPhoto($this->user_object['photo']);
                    }
                    $user->updateLastLoggedTime();
                }

                $result = false;
                if (!$exists) {
                    $result = $users_manager->createUser($user_object);
                }
                $this->result = $exists || $result;
            } catch (VkApiException $err) {
                //todo
            }
        }
    }

    public function getResult() {
        return $this->result;
    }

    public function getUser() {
        $user_vk_id = $this->vk_object['id'];
        $user = new User($this->db);
        $user->allocateUserByVkId($user_vk_id);
        return $user;
    }
}