<?php

namespace Qemy\Elibrary\Methods;

interface InterfaceMethods {

    function search($params, $api);
    function getPopular($params, $api);
    function getCategories($params, $api);
    function addAuthor($params);
    function addTicket($params);
    function addEdition($params);
}