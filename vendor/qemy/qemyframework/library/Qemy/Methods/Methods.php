<?php

namespace Qemy\Methods;

use Qemy\Db\QemyDb;

final class Methods {
    private static $method = 'post';
    private static $max_category = 8;

    private $params;
    private $db;
    private $query;
    private $category;
    private $count;
    private $offset;
    private $hash;

    private $result;

    function __construct($params, $db) {
        $this->params = $params;
        /** @var $db QemyDb */
        $this->db = $db;
        $this->SendHeaders();
    }

    private function SendHeaders() {
        header("Cache-Control: Cache-Control:no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/json; charset=utf-8");
    }

    private function SetQueryParams() {
        $this->hash = (!empty($this->params[self::$method]['hash'])) ? $this->params[self::$method]['hash'] : 0;
        $this->query = addslashes((string)trim($this->params[self::$method]['q']));
        $this->category = (!empty($this->params[self::$method]['category'])) ? intval($this->params[self::$method]['category']) : 1;
        $this->count = (!empty($this->params[self::$method]['count'])) ? intval($this->params[self::$method]['count']) : 10;
        $this->offset = (!empty($this->params[self::$method]['offset'])) ? intval($this->params[self::$method]['offset']) : 0;

        if ($this->category < 1 || $this->category > self::$max_category) {
            $this->category = 1;
        }
        if ($this->count < 1) {
            $this->count = 10;
        }
        if ($this->offset < 0) {
            $this->offset = 0;
        }
    }

    private function CreateResultContainer()
    {
        $this->result = array(
            "status" => "OK",
            "hash" => $this->hash,
            "emptyQuery" => ((strlen($this->query) == 0) ? true : false),
            "items" => array()
        );
        if (!$this->hash) {
            $this->result['status'] = 'Error';
        }
    }

    private function SearchResultCount() {
        $category = ($this->category != 1) ? "`category` = $this->category AND" : "";
        return $this->db->simpleQuery("SELECT *
								FROM `editions`
								WHERE $category `name` LIKE '%$this->query%'");
    }

    private function SearchQuery() {
        $category = ($this->category != 1) ? "`category` = $this->category AND" : "";
        return $this->db->simpleQuery("SELECT *
								FROM `editions`
								WHERE $category `name` LIKE '%$this->query%'
								ORDER BY `dl_count` DESC
								LIMIT $this->offset, $this->count");
    }

    private function ResonableSearchCount() {
        $category = ($this->category != 1) ? "`category` = $this->category AND" : "";
        return $this->db->simpleQuery("SELECT *,
				MATCH (`name`, `author`)
				AGAINST ('+".$this->query."' IN BOOLEAN MODE) as REL
				FROM `editions`
				WHERE $category MATCH (`name`, `author`) AGAINST ('+".$this->query."' IN BOOLEAN MODE)
				ORDER BY REL DESC");
    }

    private function ResonableSearchQuery() {
        $category = ($this->category != 1) ? "`category` = $this->category AND" : "";
        return $this->db->simpleQuery("SELECT *,
								MATCH (`name`, `author`)
								AGAINST ('+".$this->query."' IN BOOLEAN MODE) as REL
								FROM `editions`
								WHERE $category MATCH (`name`, `author`) AGAINST ('+".$this->query."' IN BOOLEAN MODE)
								ORDER BY REL DESC
								LIMIT $this->offset, $this->count");
    }

    public function Search()
    {
        $this->SetQueryParams();
        $this->CreateResultContainer();
        if ($this->result['emptyQuery']) {
            return $this->result;
        }

        $res = null; $res_count = null;
        $res_count = $this->ResonableSearchCount();
        if (!$res_count->num_rows) {
            $res = $this->SearchQuery();
            $res_count = $this->SearchResultCount();
        } else {
            $res = $this->ResonableSearchQuery();
        }

        $found_count = $res_count->num_rows;
        if (!$found_count) {
            $this->result['found'] = false;
            $this->result['text'] = 'По запросу «'.str_replace(array('\\'), array(''), $this->query).'» ничего не найдено.';
            $this->result['items_count'] = 0;
            $this->result['overall_items_count'] = 0;
            return $this->result;
        } else {
            $offset_found_count = ($found_count - $this->offset < 10) ? $found_count - $this->offset : 10;
            $this->result['found'] = true;
            $this->result['text'] = $this->SpecifyCount($found_count);
            $this->result['items_count'] = $offset_found_count;
            $this->result['overall_items_count'] = $found_count;
            while($row = $res->fetch_assoc()) {
                $this->result['items'][] = array(
                    "id" => $row['id'],
                    "name" => $this->ToUtf(strip_tags($row['name'])),
                    "download_url" => $row['dl_url'],
                    "author" => ((!empty($row['author']))?explode(',', $row['author']):array()),
                    "photo_big" => $row['photo_big'],
                    "category" => $row['category'],
                    "count_dl" => $row['dl_count'],
                    "file_size" => $row['file_size']
                );
            }
            $this->AddToStats($this->query, time(), $_SERVER['REMOTE_ADDR']);
        }

        return $this->result;
    }

    private function ToUtf($str) {
        return str_replace(array('&laquo;', '&raquo;', '&lt;', '&gt;', '&ldquo;', '&rdquo;', '&times;', '&Agrave;'), array('«', '»', '<', '>', '“', '”', '×', 'A'), $str);
    }

    private function AddToStats($query, $time, $ip) {
        $this->db->query("INSERT INTO `queries_stats` (`query`, `time`, `ip`) VALUES(?s, ?i, ?s)", (string)$query, intval($time), (string)$ip);
    }

    public function AddAuthor() {
        $id_edition = (!empty($this->params[self::$method]['id'])) ? $this->params[self::$method]['id'] : 0;
        $author = (!empty($this->params[self::$method]['author'])) ? $this->params[self::$method]['author'] : "NULL";
        $time = time();
        if ($id_edition != 0 && $author != "NULL") {
            $this->db->query("INSERT INTO `help_authors` (`id_edition`, `author`, `time`) VALUES(?i, ?s, ?i)", $id_edition, $author, $time);
        } else {
            return array("status" => "Error");
        }
        return array("status" => "OK");
    }

    private function SpecifyCount($count) {
        $ret_val = "";
        if ($count < 21) {
            if ($count == 0) {
                $ret_val = "Элементов не найдено";
            } else if ($count == 1) {
                $ret_val = "Найден " . $count . " элемент";
            } else if ($count > 1 && $count < 5) {
                $ret_val = "Найдено " . $count . " элемента";
            } else if ($count > 4 && $count < 21) {
                $ret_val = "Найдено " . $count . " элементов";
            }
        } else {
            if ($count % 10 == 1) {
                $ret_val = "Найден " . $count . " элемент";
            } else if ($count % 10 > 1 && $count % 10 < 5) {
                $ret_val = "Найдено " . $count . " элемента";
            } else {
                $ret_val = "Найдено " . $count . " элементов";
            }
        }
        return $ret_val;
    }

    public function SaveTicket() {
        $email = (!empty($this->params[self::$method]['email'])) ? $this->params[self::$method]['email'] : "Не указан";
        $ticket_message = (!empty($this->params[self::$method]['ticket_message'])) ? $this->params[self::$method]['ticket_message'] : 'NULL';
        $time = time();
        if ($ticket_message != 'NULL') {
            $this->db->query("INSERT INTO `tickets` (`email`, `ticket_message`, `time`) VALUES(?s, ?s, ?i)", $email, $ticket_message, $time);
        } else {
            return array("status" => "Error");
        }
        return array("status" => "OK");
    }

    public function AddEdition() {
        $link = (!empty($this->params[self::$method]['link'])) ? $this->params[self::$method]['link'] : "NULL";
        $hash = (!empty($this->params[self::$method]['hash'])) ? $this->params[self::$method]['hash'] : "NULL";
        $time = time();
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($link != "NULL" && $hash != "NULL") {
            $this->db->query("INSERT INTO `edition_offer` (`link`, `hash`, `time`, `ip`) VALUES(?s, ?s, ?i, ?s)", $link, $hash, $time, $ip);
        } else {
            return array("status" => "Error");
        }
        return array("status" => "OK");
    }

    public function GetPopular() {
        $result = array(
            "status" => "OK",
            "items" => array()
        );
        $category = isset($this->params['get']['category']) ? intval($this->params['get']['category']) : false;
        $where_category = $category && $category != 1 ? "WHERE `category` = ".$category : '';
        $res = $this->db->query("SELECT * FROM `editions` ".$where_category." ORDER BY `dl_count` DESC LIMIT 0, 30");

        while($row = $res->fetch_assoc()) {
            $result['items'][] = array(
                "id" => $row['id'],
                "name" => htmlspecialchars($row['name']),
                "download_url" => $row['dl_url'],
                "author" => ((!empty($row['author']))?explode(',', $row['author']):array()),
                "photo_big" => $row['photo_big'],
                "category" => $row['category'],
                "count_dl" => $row['dl_count'],
                "file_size" => $row['file_size']
            );
        }
        return $result;
    }
}