<?php

namespace Qemy\Elibrary\Methods;

interface InterfaceMethods {

    function getSearchResult($params);
    function getPopular($params);
    function getCategories();
    function addAuthor($params);
    function addTicket($params);
    function addEdition($params);
}