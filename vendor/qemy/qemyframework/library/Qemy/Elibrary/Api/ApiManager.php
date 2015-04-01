<?php

namespace Qemy\Elibrary\Api;

use Qemy\Db\QemyDatabase;
use Qemy\Elibrary\Api\Exception\InvalidVkAccessTokenException;
use Qemy\Elibrary\Api\Exception\MissingAccessTokenException;
use Qemy\Elibrary\Api\Exception\TooManyCreaturesTokenException;
use Qemy\Elibrary\Api\Exception\TooManyRequestException;
use Qemy\Elibrary\Api\Exception\InvalidAccessTokenException;
use Qemy\Elibrary\Request\Exception\NoSubscriptionException;
use Qemy\Elibrary\Request\RequestHandler;
use Qemy\User\Authorization\Model\AuthorizationModel;
use Qemy\User\User;

class ApiManager extends RequestHandler {

    private $db;
    private $request_type = 'request';
    private $params;

    const DEFAULT_MAX_QUERIES = 30; //count
    const DEFAULT_PERIOD = 5; //seconds
    const DEFAULT_BAN_TIME = 10; //seconds
    const DEFAULT_BAN_TIME_TOKEN = 30; //seconds

    public static $methods_without_sub = array(
        'accountGetInfo',
        'search',
        'getPopular',
        'getPopularForWeek',
        'getDocument',
        'getCategories',
        'getFaves',
        'addFave',
        'deleteFave',
        'deleteAllFaves'
    );

    /**
     * @param $db QemyDatabase
     * @param $user User
     */
    function __construct($db) {
        $this->db = $db;
        parent::__construct($db);
    }

    public function create($method, $params) {
        try {
            if ($method == 'signin') {
                return $this->createAccessToken($params);
            }
            return $this->run($method, $params);
        } catch (NoSubscriptionException $err) {
            return $err->getError();
        } catch (InvalidAccessTokenException $err) {
            return $err->getError();
        } catch (InvalidVkAccessTokenException $err) {
            return $err->getError();
        } catch (MissingAccessTokenException $err) {
            return $err->getError();
        } catch (TooManyCreaturesTokenException $err) {
            return $err->getError();
        } catch (TooManyRequestException $err) {
            return $err->getError();
        }
    }

    public function run($method, $params) {
        $this->params = $params[$this->request_type];
        $api_user = $this->getApiUser($this->params);
        $user = new User($this->db);
        $user->allocateUserById($api_user['user_id']);
        $user->updateRecentActivtyTime();

        if (!$user->hasSubscription() && !in_array($method, self::$methods_without_sub)) {
            throw new NoSubscriptionException($this->params);
        }

        $microtime = microtime(true);
        if ($this->isBannedUser($api_user) && $this->expiredBanUser($api_user)) {
            $this->unbanUser($api_user);
        }
        if (!$this->isBannedUser($api_user)) {
            if ($api_user['recent_activity'] + self::DEFAULT_PERIOD >= $microtime) {
                $api_user['recent_count_activity']++;
            } else {
                $api_user['recent_count_activity'] = 1;
                $api_user['recent_activity'] = $microtime;
                $this->clearUserState($api_user);
            }
            if ($api_user['recent_count_activity'] > self::DEFAULT_MAX_QUERIES) {
                $api_user['ban'] = !0;
                $api_user['unlock_time'] = time() + self::DEFAULT_BAN_TIME;
                $this->banUser($api_user);
            } else {
                $this->incrementUserCountActivity($api_user);
            }
            if (!$this->isBannedUser($api_user)) {
                $this->updateUserStats($api_user, $method);

                $this->params['user'] = $user;
                $methods = new RequestHandler($this->db);
                return array(
                    "response" => $methods->$method($this->params)
                );
            } else {
                throw new TooManyRequestException($this->params, $api_user['unlock_time']);
            }
        } else {
            throw new TooManyRequestException($this->params, $api_user['unlock_time']);
        }
    }

    public function getApiUser($params) {
        $access_token = $params['access_token'];
        if (!empty($access_token)) {
            $access_token = $this->db->real_escape_string($access_token);
            $result = $this->db->query(
                "SELECT *
                FROM `api_users`
                WHERE `access_token` = ?s",
                (string)$access_token
            );
            if (!$result->num_rows) {
                throw new InvalidAccessTokenException($params);
            }
            return $result->fetch_array();
        }
        throw new MissingAccessTokenException($params);
    }

    protected function isBannedUser(&$user) {
        return !!$user['ban'];
    }

    protected function expiredBanUser(&$user) {
        return $user['unlock_time'] - time() <= 0;
    }

    protected function unbanUser(&$user) {
        $user['ban'] = 0;
        $this->db->query("UPDATE `api_users` SET `ban` = 0 WHERE `id` = ?i", $user['id']);
    }

    protected function clearUserState(&$user) {
        $this->db->query("UPDATE `api_users` SET `recent_activity` = {$user['recent_activity']}, `recent_count_activity` = 1 WHERE `id` = ?i", $user['id']);
    }

    protected function banUser($user) {
        $this->db->query("UPDATE `api_users` SET `ban` = 1, `unlock_time` = ?i, `recent_count_activity` = 0 WHERE `id` = ?i", $user['unlock_time'], $user['id']);
    }

    protected function incrementUserCountActivity($user) {
        $this->db->query("UPDATE `api_users` SET `recent_count_activity` = ?i WHERE `id` = ?i", $user['recent_count_activity'], $user['id']);
    }

    protected function updateUserStats($user, $method) {
        switch ($method) {
            case 'search':
                $this->db->query("UPDATE `api_users` SET `count_queries_search` = `count_queries_search` + 1 WHERE `id` = ?i", $user['id']);
                break;
            case 'getPopular':
                $this->db->query("UPDATE `api_users` SET `count_queries_getpopular` = `count_queries_getpopular` + 1 WHERE `id` = ?i", $user['id']);
                break;
        }
    }

    protected function createAccessToken($params) {
        $params = $params['request'];
        $time = time();
        $vk_access_token = $params['vk_access_token'];
        $auth = new AuthorizationModel($this->db);
        $auth->toggleApi();
        $auth->setAuthParams(array(
            'access_token' => $vk_access_token
        ));
        $auth->auth();
        $result = $auth->getResult();
        if (!$result) {
            throw new InvalidVkAccessTokenException($params);
        }
        $user = $auth->getUser();
        $too_many_requests = $this->db->query("SELECT * FROM `api_users` WHERE `creation_time` + ".self::DEFAULT_BAN_TIME_TOKEN." > ?i AND `user_id` = ?i", $time, $user->getId());
        if ($too_many_requests->num_rows) {
            throw new TooManyCreaturesTokenException($params);
        }
        $access_token = $this->createRandomToken();
        $this->db->query("INSERT INTO `api_users` (`user_id`, `access_token`, `creation_time`, `creation_ip`) VALUES (?i, ?s, ?i, ?s)", $user->getId(), $access_token, $time, $_SERVER['REMOTE_ADDR']);
        return array(
            "response" => array(
                "access_token" => $access_token
            )
        );
    }

    private function createRandomToken($size = 64) {
        for ($str = 'abcdef0123456789', $i = 0, $hash = ""; $i < $size; ++$i)
            $hash .= $str[rand(0, strlen($str) - 1)];
        return $hash;
    }
}