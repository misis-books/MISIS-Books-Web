<?php

namespace Qemy\Elibrary\Request;

use Qemy\Db\QemyDatabase;
use Qemy\Elibrary\Methods\Documents\Documents;
use Qemy\Elibrary\Methods\Fave\Faves;
use Qemy\User\User;

class RequestHandler {

    private $db;

    /**
     * @param $db QemyDatabase
     * @param $user User
     */
    protected function __construct($db) {
        $this->db = $db;
    }

    protected function search($params) {
        /** @var User $user */
        $user = $params['user'];
        $user->incrementQueriesCount();
        $documents = new Documents($this->db);
        $result = $documents->search($params['q'], $params['category'], $params['offset'], $params['count'], $params['fields'], $user->getId());
        $result['sid'] = $params['sid'];
        return $result;
    }

    protected function getPopular($params) {
        /** @var User $user */
        $user = $params['user'];
        $documents = new Documents($this->db);
        $result = $documents->getPopular($params['category'], $params['offset'], $params['count'], $params['fields'], $user->getId());
        return $result;
    }

    protected function getPopularForWeek($params) {
        /** @var User $user */
        $user = $params['user'];
        $documents = new Documents($this->db);
        $result = $documents->getPopularForWeek($params['category'], $params['offset'], $params['count'], $params['fields'], $user->getId());
        return $result;
    }

    protected function getDocument($params) {
        /** @var User $user */
        $user = $params['user'];
        $documents = new Documents($this->db);
        $result = $documents->getDocument($params['edition_id'], $params['fields'], $user->getId());
        return $result;
    }

    protected function getCategories($params) {
        $documents = new Documents($this->db);
        $result = $documents->getCategories();
        return $result;
    }

    protected function getFaves($params) {
        /** @var User $user */
        $user = $params['user'];
        $faves = new Faves($this->db);
        $faves->setUser($user);
        $result = $faves->getFaves($params['count'], $params['offset'], $params['category'], $params['fields']);
        return $result;
    }

    protected function addFave($params) {
        /** @var User $user */
        $user = $params['user'];
        $faves = new Faves($this->db);
        $faves->setUser($user);
        $result = $faves->addFave($params['edition_id']);
        return $result;
    }

    protected function deleteFave($params) {
        /** @var User $user */
        $user = $params['user'];
        $faves = new Faves($this->db);
        $faves->setUser($user);
        $result = $faves->deleteFave($params['edition_id']);
        return $result;
    }

    protected function deleteAllFaves($params) {
        /** @var User $user */
        $user = $params['user'];
        $faves = new Faves($this->db);
        $faves->setUser($user);
        $result = $faves->deleteAllFaves();
        return $result;
    }

    protected function accountGetInfo($params) {
        /** @var User $user */
        $user = $params['user'];
        $result = $user->getFullInfo();
        return $result;
    }
}