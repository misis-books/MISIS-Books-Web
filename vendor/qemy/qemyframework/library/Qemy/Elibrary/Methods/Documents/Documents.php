<?php

namespace Qemy\Elibrary\Methods\Documents;

use Qemy\Db\QemyDatabase;

class Documents extends AbstractDocuments implements DocumentsInterface {

    private $db;

    /**
     * @param $db QemyDatabase
     */
    function __construct(&$db) {
        $this->db = $db;
        parent::__construct($db);
    }

    public function search($q, $category = 1, $offset = 0, $count = 10, $fields = 'all', $user_id = 1) {
        $q = $this->db->real_escape_string($q);
        $query_cl = $this->invertKeyboard($q);
        $cl_flag = false;
        $category = (!empty($category)) ? intval($category) : 1;
        $count = (!empty($count)) ? intval($count) : 10;
        $offset = (!empty($offset)) ? intval($offset) : 0;

        $result = array(
            "status" => 'OK',
            "q" => $q,
            "empty_query" => !strlen($q)
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
        if (!strlen($q)) {
            return $result;
        }

        list($res, $res_count) = $this->searchQuery($q, $category, $count, $offset, $user_id);
        $found_count = $res_count->num_rows;

        if (!$found_count) {
            /** @var $res \mysqli_result */
            list($res, $res_count) = $this->searchQuery($query_cl, $category, $count, $offset, $user_id);
            $cl_flag = true;
        }
        $found_count = $res_count->num_rows;

        if (!$found_count) {
            $result['found'] = false;
            $result['items_count'] = 0;
            $result['all_items_count'] = 0;
            $result['items'] = array();
        } else {
            $offset_found_count = $res->num_rows;
            $result['found'] = true;
            $result['view_count'] = $this->searchSpecifyCount($found_count);
            $result['items_count'] = $offset_found_count;
            $result['all_items_count'] = $found_count;
            $result['lang_cl'] = $cl_flag;
            $result['category'] = array(
                'id' => intval($category),
                'name' => $this->getCategoryName($category)
            );
            $result['items'] = $this->getItemsWithFields($res, $fields);
        }

        $this->addStats($user_id, $q, time(), $_SERVER['REMOTE_ADDR']);
        return $result;
    }

    public function getPopular($category = 1, $offset = 0, $count = 10, $fields = 'all', $user_id = 1) {
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

        $where_category = $category != 1 ? ("WHERE `editions`.`category` = ".$category) : '';
        $res = $this->db->query(
            "SELECT faves.*,editions.*
            FROM `editions`
            LEFT JOIN faves ON faves.edition_id = editions.id AND faves.user_id = ?i
            ".$where_category."
            ORDER BY `dl_count` DESC
            LIMIT ?i, ?i",
            $user_id, $offset, $count
        );
        $result['items_count'] = $res->num_rows;
        $result['items'] = $this->getItemsWithFields($res, $fields);
        $result['category'] = array(
            'id' => intval($category),
            'name' => $this->getCategoryName($category)
        );
        $res = $this->db->query(
            "SELECT faves.*,editions.*
            FROM `editions`
            LEFT JOIN faves ON faves.edition_id = editions.id AND faves.user_id = ?i
            ".$where_category."
            ORDER BY `dl_count` DESC",
            $user_id
        );
        $result['all_items_count'] = $res->num_rows;

        return $result;
    }

    public function getPopularForWeek($category = 1, $offset = 0, $count = 10, $fields = 'all', $user_id = 1) {
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

        $where_category = $category != 1 ? ("WHERE `editions`.`category` = ".$category) : '';
        $res = $this->db->query(
            "SELECT `static_popular`.*, `editions`.*,`faves`.*
            FROM `static_popular`
            LEFT JOIN `editions`
            ON `static_popular`.id_edition = `editions`.id
            LEFT JOIN faves ON faves.edition_id = editions.id AND faves.user_id = ?i
            ".$where_category."
            ORDER BY `static_popular`.week_dl_count DESC
            LIMIT ?i, ?i",
            $user_id, $offset, $count
        );
        $result['items_count'] = $res->num_rows;
        $result['items'] = $this->getItemsWithFields($res, $fields);
        $result['category'] = array(
            'key' => intval($category),
            'value' => $this->getCategoryName($category)
        );

        return $result;
    }

    public function getCategories() {
        $response['response']['items_count'] = 8;
        for ($i = 1; $i <= 8; ++$i) {
            $response['response']['categories'][] = array(
                "key" => $i,
                "category_name" => $this->getCategoryName($i),
                "color_hex" => $this->getCategoryColor($i)
            );
        }
        return $response;
    }
}