<?php

namespace Qemy\Elibrary\Methods\Fave;

use Qemy\Elibrary\Methods\Documents\AbstractDocuments;
use Qemy\User\User;

class Faves extends AbstractDocuments implements FavesInterface {

    private $db;

    private $user;

    /**
     * @param \Qemy\Db\QemyDatabase $db
     * @param User $user
     */
    function __construct($db, $user = null) {
        $this->db = $db;
        $this->user = $user;
    }

    /**
     * @param $user User
     */
    public function setUser($user) {
        $this->user = $user;
    }

    public function getFaves($count = 10, $offset = 0, $category = 1, $fields = 'all') {
        $category = (!empty($category)) ? intval($category) : 1;
        $count = (!empty($count)) ? intval($count) : 10;
        $offset = (!empty($offset)) ? intval($offset) : 0;

        $result = array(
            "status" => "OK",
            "items" => array()
        );
        if ($category < 1 || $category > 8) {
            $category = 1;
        }
        if ($count < 1) {
            $count = 10;
        }
        if ($offset < 0) {
            $offset = 0;
        }
        if ($count > 200) {
            $count = 200;
        }

        $where_category = $category != 1 ? ("AND editions.category = ".$category) : '';
        $res = $this->db->query(
            "SELECT faves.*,editions.*
            FROM `faves`
            LEFT JOIN editions
            ON faves.edition_id = editions.id
            WHERE faves.user_id = ?i $where_category
            ORDER BY faves.creation_time DESC
            LIMIT ?i, ?i",
            $this->user->getId(),
            $offset, $count
        );
        $result['items_count'] = $res->num_rows;
        $result['items'] = $this->getItemsWithFields($res, $fields);

        $res = $this->db->query(
            "SELECT editions.*
            FROM `faves`
            LEFT JOIN editions
            ON faves.edition_id = editions.id
            WHERE faves.user_id = ?i $where_category",
            $this->user->getId()
        );
        $result['all_items_count'] = $res->num_rows;

        $result['category'] = array(
            'id' => intval($category),
            'name' => $this->getCategoryName($category)
        );

        return $result;
    }

    public function addFave($edition_id) {
        $result = array(
            "status" => "OK",
            "result" => false
        );
        $edition_ids = array_values(array_unique(explode(',', $edition_id)));
        $edition_ids_whitelist = array();
        $ids_num = 0;
        foreach ($edition_ids as $edition_id_val) {
            if (is_numeric($edition_id_val) && $ids_num <= 200) {
                array_push($edition_ids_whitelist, intval($edition_id_val));
                $ids_num++;
            }
        }
        if (!count($edition_ids_whitelist)) {
            return $result;
        }
        $result['inserted_ids'] = array();
        foreach ($edition_ids_whitelist as $edition_id) {
            $exists_check = $this->db->query(
                "SELECT *
                FROM `faves`
                WHERE user_id = ?i AND edition_id = ?i",
                $this->user->getId(),
                $edition_id
            );
            if (!$exists_check->num_rows) {
                array_push($result['inserted_ids'], $edition_id);
                $this->db->query(
                    "INSERT INTO `faves` (user_id, edition_id, creation_time)
                    VALUES(?i, ?i, ?i)",
                    $this->user->getId(),
                    $edition_id,
                    time()
                );
            }
        }
        $this->db->query("COMMIT");
        $result['result'] = true;
        return $result;
    }

    public function deleteFave($edition_id) {
        $result = array(
            "status" => "OK",
            "result" => false
        );
        $edition_ids = array_values(array_unique(explode(',', $edition_id)));
        $edition_ids_whitelist = array();
        $ids_num = 0;
        foreach ($edition_ids as $edition_id_val) {
            if (is_numeric($edition_id_val) && $ids_num <= 200) {
                array_push($edition_ids_whitelist, intval($edition_id_val));
                $ids_num++;
            }
        }
        if (!count($edition_ids_whitelist)) {
            return $result;
        }
        $result['deleted_ids'] = array();
        foreach ($edition_ids_whitelist as $edition_id) {
            array_push($result['deleted_ids'], $edition_id);
            $this->db->query(
                "DELETE FROM `faves`
                WHERE `user_id` = ?i AND `edition_id` = ?i",
                $this->user->getId(),
                $edition_id
            );
        }
        $this->db->query("COMMIT");
        $result['result'] = true;
        return $result;
    }

    public function deleteAllFaves() {
        $result = array(
            "status" => "OK"
        );
        $this->db->query(
            "DELETE FROM `faves`
            WHERE `user_id` = ?i",
            $this->user->getId()
        );
        $this->db->query("COMMIT");
        $result['result'] = true;
        return $result;
    }
}