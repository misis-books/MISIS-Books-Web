<?php

namespace Qemy\User;

use Qemy\User\Authorization\CheckAuthorization;

interface UserInterface {

    function setTableRow($row);
    function allocateUserById($id);
    function allocateUserByVkId($email);
    function getObject();
    function setCheckAuthorization(CheckAuthorization& $class);
    function isAuth();

    function getId();
    function getVkId();
    function getAccessLevel();
    function getFirstName();
    function getLastName();
    function getViewName();
    function getVkProfileReference();

    function getRegisterTime();
    function getRegisterDate();

    function getLastLoggedTime();
    function getLastLoggedDate();
    function getLastLoggedEllapsed();

    function getDownloadCount();
    function getQueriesCount();

    function getAccessKeys();
    function getAccessKeysArray();
    function accessKeyExists($target_key);
    function addAccessKey($new_key);
    function deleteAccessKey($target_key);

    function getRecentActivityTime();
    function getRecentActivityDate();
    function getRecentActivityEllapsed();

    function isOnline();

    function setAccessLevel($level);
    function setFirstName($first_name);
    function setLastName($second_name);
    function setVkProfileDomain($domain);

    function incrementDownloadCount();
    function incrementQueriesCount();

    function updateLastLoggedTime();
    function updateRecentActivtyTime();
}