<?php

namespace Qemy\Elibrary\Api\Manager;

use Qemy\Db\QemyDb;
use Qemy\Elibrary\Api\Manager\Exception\InvalidAccessTokenException;
use Qemy\Elibrary\Api\Manager\Exception\MissingAccessTokenException;
use Qemy\Elibrary\Api\Manager\Exception\TooManyCreaturesTokenException;

abstract class AbstractManager {

    private $db;

    const DEFAULT_MAX_QUERIES = 15; //count
    const DEFAULT_PERIOD = 5; //seconds
    const DEFAULT_BAN_TIME = 10; //seconds
    const DEFAULT_BAN_TIME_TOKEN = 60; //seconds

    function __construct(&$db) {
        /** @var $db QemyDb */
        $this->db = $db;
    }

    protected function getUser($params) {
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

    protected function createAccessTokenMethod($params) {
        $time = time();
        $search_ip = $this->db->query("SELECT * FROM `api_users` WHERE `creation_time` + ".self::DEFAULT_BAN_TIME_TOKEN." > ?i AND `creation_ip` = ?s", $time, $params['ip']);
        if ($search_ip->num_rows) {
            throw new TooManyCreaturesTokenException($params);
        }
        $access_token = $this->createRandomString();
        $this->db->query("INSERT INTO `api_users` (`access_token`, `creation_time`, `creation_ip`) VALUES (?s, ?i, ?s)", $access_token, $time, $params['ip']);
        return array(
            "response" => array(
                "access_token" => $access_token
            )
        );
    }

    private function createRandomString($size = 64) {
        for ($str = 'abcdef0123456789', $i = 0, $hash = ""; $i < $size; ++$i)
            $hash .= $str[rand(0, strlen($str) - 1)];
        return $hash;
    }
}