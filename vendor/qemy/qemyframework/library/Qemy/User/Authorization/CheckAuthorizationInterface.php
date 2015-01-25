<?php

namespace Qemy\User\Authorization;

interface CheckAuthorizationInterface {

    function check();
    function getResult();
    function getUserRow();
}