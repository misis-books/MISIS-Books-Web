<?php

namespace Qemy\Elibrary\Api;

interface ApiInterface {

    function createAccessToken();
    function search($params);
    function getPopular($params);
    function getCategories($params);
}