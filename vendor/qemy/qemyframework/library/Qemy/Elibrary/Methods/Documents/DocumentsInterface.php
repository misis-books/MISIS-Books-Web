<?php

namespace Qemy\Elibrary\Methods\Documents;

interface DocumentsInterface {

    function search($q, $category, $offset, $count, $fields);
    function getPopular($category, $offset, $count, $fields);
    function getPopularForWeek($category, $offset, $count, $fields);
    function getCategories();
}