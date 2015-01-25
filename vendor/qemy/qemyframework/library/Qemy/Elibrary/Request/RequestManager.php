<?php

namespace Qemy\Elibrary\Request;

use Qemy\Db\QemyDatabase;
use Qemy\Elibrary\Request\Exception\NoSubscriptionException;
use Qemy\Elibrary\Request\Exception\NotAuthorizedUserException;
use Qemy\User\User;

class RequestManager extends RequestHandler {

    private $db;
    private $request_type;
    private $user;

    const POST_REQUEST_METHOD = 'post';
    const GET_REQUEST_METHOD = 'get';

    /**
     * @param $db QemyDatabase
     * @param $user User
     */
    function __construct($db, $user = null) {
        $this->db = $db;
        $this->request_type = self::POST_REQUEST_METHOD;
        $this->user = $user;
        parent::__construct($db);
    }

    public function setRequestMethod($request_type) {
        $this->request_type = $request_type;
    }

    /**
     * @param $user User
     */
    public function setInitiator($user) {
        $this->user = $user;
    }

    public function create($method, $params) {
        $params = $params[$this->request_type];
        $params['user'] = $this->user;
        try {
            if (!$this->user->isAuth()) {
                throw new NotAuthorizedUserException($params);
            }
            if (!$this->user->hasSubscription()) {
                throw new NoSubscriptionException($params);
            }
            $result = $this->$method($params);
            return $result;
        } catch (NotAuthorizedUserException $err) {
            return $err->getError();
        } catch (NoSubscriptionException $err) {
            return $err->getError();
        }
    }
}