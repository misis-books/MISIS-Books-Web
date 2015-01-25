<?php

namespace Qemy\User\Authorization;

interface AuthorizationInterface {

    function setData($data);
    function signIn();
    function getResult();
}