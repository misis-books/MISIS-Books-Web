<?php

namespace Qemy\Elibrary\Api\Manager;

interface ManagerInterface {

    function setParams($params);
    function setMethod($method);
    function getMethodResult();
}