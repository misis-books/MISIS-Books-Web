<?php

namespace Doc\Model;

use Qemy\Core\Application;
use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\Api\ApiManager;
use Qemy\Elibrary\Api\Exception\InvalidAccessTokenException;
use Qemy\Elibrary\Api\Exception\MissingAccessTokenException;
use Qemy\Elibrary\Elibrary;
use Qemy\Elibrary\Request\Exception\NoSubscriptionException;
use Qemy\User\Authorization\CheckAuthorization;
use Qemy\User\User;

class IndexModel extends AbstractModel {

    public function main() {
        $params = $this->getRequestParams()['request'];
        $is_api = isset($params['access_token']);
        if ($is_api) {
            try {
                $api_manager = new ApiManager($this->getQemyDb());
                $api_user = $api_manager->getApiUser($params);
                $user = new User($this->getQemyDb());
                $user->allocateUserById($api_user['user_id']);
                if ($user->isEmpty()) {
                    throw new InvalidAccessTokenException($params);
                }
            } catch (InvalidAccessTokenException $err) {
                header("HTTP/1.1 403 Forbidden");
                Application::setContentType('json');
                echo json_encode($err->getError());
                return $this;
            } catch (MissingAccessTokenException $err) {
                header("HTTP/1.1 403 Forbidden");
                Application::setContentType('json');
                echo json_encode($err->getError());
                return $this;
            }
        } else {
            $check_auth = new CheckAuthorization($this->getQemyDb());
            $check_auth->check();

            $user = new User($this->getQemyDb(), $check_auth->getUserRow());
            $user->setCheckAuthorization($check_auth);
        }

        $elib = new Elibrary($this->getQemyDb(), $user);
        if ($is_api) {
            $elib->toggleApi();
        }
        $elib->ShowFile($params['id']);
        return $this;
    }
}