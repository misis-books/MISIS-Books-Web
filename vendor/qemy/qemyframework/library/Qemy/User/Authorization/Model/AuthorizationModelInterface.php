<?php

namespace Qemy\User\Authorization\Model;

interface AuthorizationModelInterface {

    /*
     * @var $user_object array
     * user data
     */
    function setUserObject($user_object);

    /*
     * @var $params array
     */
    function setAuthParams($params);

    /*
     * default — api off
     */
    function toggleApi();

    /**
     * invoke the auth action
     */
    function auth();

    /**
     * @return bool
     */
    function getResult();
}