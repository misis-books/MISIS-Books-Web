<?php

namespace Qemy\Elibrary\Methods\Fave;

interface FavesInterface {

    function setUser($user);
    function getFaves($count, $offset, $category, $fields);
    function addFave($edition_id);
    function deleteFave($edition_id);
    function deleteAllFaves();
}