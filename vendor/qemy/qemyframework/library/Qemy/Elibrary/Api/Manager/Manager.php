<?php

namespace Qemy\Elibrary\Api\Manager;

use Qemy\Elibrary\Api\Manager\Exception\InvalidAccessTokenException;
use Qemy\Elibrary\Api\Manager\Exception\MissingAccessTokenException;
use Qemy\Elibrary\Api\Manager\Exception\TooManyCreaturesTokenException;
use Qemy\Elibrary\Api\Manager\Exception\TooManyRequestException;
use Qemy\Elibrary\Methods\Methods;

class Manager extends AbstractManager implements ManagerInterface {

    private $params, $method, $db;

    function __construct(&$db) {
        $this->db = $db;
        parent::__construct($db);
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function getMethodResult() {
        try {
            $user = $this->getUser($this->params);
            $microtime = microtime(true);
            if ($this->isBannedUser($user) && $this->expiredBanUser($user)) {
                $this->unbanUser($user);
            }
            if (!$this->isBannedUser($user)) {
                if ($user['recent_activity'] + self::DEFAULT_PERIOD >= $microtime) {
                    $user['recent_count_activity']++;
                } else {
                    $user['recent_count_activity'] = 1;
                    $user['recent_activity'] = $microtime;
                    $this->clearUserState($user);
                }
                if ($user['recent_count_activity'] > self::DEFAULT_MAX_QUERIES) {
                    $user['ban'] = !0;
                    $user['unlock_time'] = time() + self::DEFAULT_BAN_TIME;
                    $this->banUser($user);
                } else {
                    $this->incrementUserCountActivity($user);
                }
                if (!$this->isBannedUser($user)) {
                    $this->updateUserStats($user, $this->method);

                    $methods = new Methods($this->db);
                    $method_name = $this->method;
                    return array(
                        "response" => $methods->$method_name($this->params, 1)
                    );
                } else {
                    throw new TooManyRequestException($this->params, $user['unlock_time']);
                }
            } else {
                throw new TooManyRequestException($this->params, $user['unlock_time']);
            }
        } catch (MissingAccessTokenException $err) {
            return $err->getErrorResult();
        } catch (InvalidAccessTokenException $err) {
            return $err->getErrorResult();
        } catch (TooManyRequestException $err) {
            return $err->getErrorResult();
        }
    }

    public function createAccessToken() {
        try {
            return $this->createAccessTokenMethod($this->params);
        } catch (TooManyCreaturesTokenException $err) {
            return $err->getErrorResult();
        }
    }
}