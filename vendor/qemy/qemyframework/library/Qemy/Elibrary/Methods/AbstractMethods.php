<?php

namespace Qemy\Elibrary\Methods;

use Qemy\Db\QemyDb;

abstract class AbstractMethods {

    /** @var $db QemyDb */
    private $db;

    private $cl_flag = false;

    function __construct(&$db) {
        $this->db = $db;
    }

    protected function searchMethod($params, $api = 0) {
        $result = array(
            "status" => "OK",
            "query" => $params['query'],
            "emptyQuery" => ((strlen($params['query']) == 0)),
            "items" => array()
        );
        if ($params['category'] < 1 || $params['category'] > 8) {
            $this->category = 1;
        }
        if ($params['count'] < 1) {
            $params['count'] = 10;
        }
        if ($params['offset'] < 0) {
            $params['offset'] = 0;
        }
        if ($params['count'] > 200) {
            $params['count'] = 200;
        }
        if ($params['emptyQuery']) {
            return $result;
        }

        list($res, $res_count) = $this->searchQuery($params['query'], $params['category'], $params['count'], $params['offset']);

        $found_count = $res_count->num_rows;
        if (!$found_count) {
            /** @var $res \mysqli_result */
            list($res, $res_count) = $this->searchQuery($params['query_cl'], $params['category'], $params['count'], $params['offset']);
            $this->cl_flag = true;
        }

        $found_count = $res_count->num_rows;
        if (!$found_count) {
            $result['found'] = false;
            $result['text'] = 'По запросу «'.$params['query'].'» ничего не найдено.';
            $result['items_count'] = 0;
            $result['all_items_count'] = 0;
            return $result;
        } else {
            $offset_found_count = $res->num_rows;
            $result['found'] = true;
            $result['text'] = $this->searchSpecifyCount($found_count);
            $result['items_count'] = $offset_found_count;
            $result['all_items_count'] = $found_count;
            $result['lang_cl'] = $this->cl_flag;
            $result['category'] = array(
                'key' => $params['category'],
                'value' => $this->getCategoryName($params['category'])
            );
            $index = $params['offset'] + 1;
            while($row = $res->fetch_assoc()) {
                $size_in_bytes = $this->getSizeInBytes($row['file_size']);
                $result['items'][] = array(
                    "index" => $index++,
                    "id" => intval($row['id']),
                    "name" => $this->toUtf(strip_tags($row['name'])),
                    "download_url" => $row['dl_url'],
                    "authors" => ((!empty($row['author'])) ? explode(',', $row['author']) : array()),
                    "photo_big" => $row['photo_big'],
                    "photo_small" => $row['photo_small'],
                    "category" => intval($row['category']),
                    "count_dl" => intval($row['dl_count']),
                    "file_url" => $row['file_url'],
                    "file_size" => $row['file_size'],
                    "file_size_in_bytes" => $size_in_bytes ? $size_in_bytes : 0
                );
            }
            $this->addStats($params['query'], time(), $_SERVER['REMOTE_ADDR'], $api);
        }
        return $result;
    }

    private function getExtSearchResultsAll($query, $category) {
        $category = ($category != 1) ? "`category` = $category AND" : "";
        return $this->db->simpleQuery(
            "SELECT *,
            MATCH (`name`, `author`)
            AGAINST ('+".$query."' IN BOOLEAN MODE) as REL
            FROM `editions`
            WHERE $category MATCH (`name`, `author`) AGAINST ('+".$query."' IN BOOLEAN MODE)
            ORDER BY REL DESC"
        );
    }

    private function getSearchResults($query, $category, $count, $offset) {
        $category = ($category != 1) ? "`category` = $category AND" : "";
        return $this->db->simpleQuery(
            "SELECT *
            FROM `editions`
            WHERE $category `name` LIKE '%$query%'
            ORDER BY `dl_count` DESC
            LIMIT $offset, $count"
        );
    }

    private function getSearchResultsAll($query, $category) {
        $category = ($category != 1) ? "`category` = $category AND" : "";
        return $this->db->simpleQuery(
            "SELECT *
            FROM `editions`
            WHERE $category `name` LIKE '%$query%'"
        );
    }

    private function getExtSearchResults($query, $category, $count, $offset) {
        $category = ($category != 1) ? "`category` = $category AND" : "";
        return $this->db->simpleQuery(
            "SELECT *,
            MATCH (`name`, `author`)
            AGAINST ('+".$query."' IN BOOLEAN MODE) as REL
            FROM `editions`
            WHERE $category MATCH (`name`, `author`) AGAINST ('+".$query."' IN BOOLEAN MODE)
            ORDER BY REL DESC
            LIMIT $offset, $count"
        );
    }

    protected function invertKeyboard($text) {
        $converter = array(
            'q' => 'й',   ']' => 'ъ',   '\'' => 'э',
            'w' => 'ц',   'a' => 'ф',   'z' => 'я',
            'e' => 'у',   's' => 'ы',   'x' => 'ч',
            'r' => 'к',   'd' => 'в',   'c' => 'с',
            't' => 'е',   'f' => 'а',   'v' => 'м',
            'y' => 'н',   'g' => 'п',   'b' => 'и',
            'u' => 'г',   'h' => 'р',   'n' => 'т',
            'i' => 'ш',   'j' => 'о',   'm' => 'ь',
            'o' => 'щ',   'k' => 'л',   ',' => 'б',
            'p' => 'з',   'l' => 'д',   '.' => 'ю',
            '[' => 'х',   ';' => 'ж',   '/' => '.',
            '`' => 'ё',
            'й' => 'q',   'ф' => 'a',   'я' => 'z',
            'ц' => 'w',   'ы' => 's',   'ч' => 'x',
            'у' => 'e',   'в' => 'd',   'с' => 'c',
            'к' => 'r',   'а' => 'f',   'м' => 'v',
            'е' => 't',   'п' => 'g',   'и' => 'b',
            'н' => 'y',   'р' => 'h',   'т' => 'n',
            'г' => 'u',   'о' => 'j',   'ь' => 'm',
            'ш' => 'i',   'л' => 'k',   'б' => ',',
            'щ' => 'o',   'д' => 'l',   'ю' => '.',
            'х' => '[',   'э' => '\'',  'ё' => '`',
            'ъ' => ']'
        );
        return strtr($text, $converter);
    }

    private function searchQuery($query, $category, $count, $offset) {
        $res = null; $res_count = null;
        $res_count = $this->getExtSearchResultsAll($query, $category);
        if (!$res_count->num_rows) {
            $res = $this->getSearchResults($query, $category, $count, $offset);
            $res_count = $this->getSearchResultsAll($query, $category);
        } else {
            $res = $this->getExtSearchResults($query, $category, $count, $offset);
        }
        return array(
            $res,
            $res_count
        );
    }

    private function searchSpecifyCount($count) {
        $ret_val = "";
        $word = "документ";
        $array_of_suf_unique = ['', 'a', 'ов'];
        $array_of_suf_dozen = ['ов', '', 'а', 'а', 'а', 'ов', 'ов', 'ов', 'ов', 'ов'];
        $array_of_suf_find = ['', 'о'];
        $mod = $count % 100;
        if ($mod >= 11 && $mod <= 14) {
            $ret_val = 'Найден'.$array_of_suf_find[1].' '.$count.' '.$word.$array_of_suf_unique[2];
        } else {
            $mod %= 10;
            $ret_val = 'Найден'.$array_of_suf_find[(($mod == 1) ? 0 : 1)].' '.$count.' '.$word.$array_of_suf_dozen[$mod];
        }
        return $ret_val;
    }

    private function toUtf($str) {
        return str_replace(array('&laquo;', '&raquo;', '&lt;', '&gt;', '&ldquo;', '&rdquo;', '&times;', '&Agrave;'), array('«', '»', '<', '>', '“', '”', '×', 'A'), $str);
    }

    private function getCategoryName($key) {
        if (!empty($key) && preg_match("/^[0-9]$/i", $key)) {
            $categories = array('Все', 'Пособия', 'Дипломы', 'Сборники научных трудов', 'Монографии', 'Книги МИСиС', 'Авторефераты диссертаций', 'Разное');
            return $categories[($key - 1) % 8];
        }
        return 'Не определено';
    }

    private function addStats($query, $time, $ip, $api = 0) {
        if (!empty($query)) {
            $this->db->query("INSERT INTO `queries_stats` (`query`, `time`, `ip`, `api`) VALUES(?s, ?i, ?s, ?i)", (string)$query, intval($time), (string)$ip, $api);
        }
    }

    protected function realEscapeString($text) {
        return $this->db->real_escape_string($text);
    }

    protected function getPopularMethod($params) {
        $result = array(
            "status" => "OK",
            "items" => array()
        );
        if ($params['category'] < 1 || $params['category'] > 8) {
            $this->category = 1;
        }
        if ($params['count'] < 1) {
            $params['count'] = 30;
        }
        if ($params['offset'] < 0) {
            $params['offset'] = 0;
        }
        if ($params['count'] > 200) {
            $params['count'] = 200;
        }

        $where_category = $params['category'] != 1 ? ("WHERE `category` = ".$params['category']) : '';
        $res = $this->db->query(
            "SELECT *
            FROM `editions`
            ".$where_category."
            ORDER BY `dl_count` DESC
            LIMIT ?i, ?i", $params['offset'], $params['count']);

        $result['category'] = array(
            'key' => intval($params['category']),
            'value' => $this->getCategoryName($params['category'])
        );
        $result['items_count'] = $res->num_rows;
        $index = $params['offset'] + 1;
        while ($row = $res->fetch_assoc()) {
            $size_in_bytes = $this->getSizeInBytes($row['file_size']);
            $result['items'][] = array(
                "index" => $index++,
                "id" => intval($row['id']),
                "name" => $this->toUtf(strip_tags($row['name'])),
                "download_url" => $row['dl_url'],
                "authors" => ((!empty($row['author']))?explode(',', $row['author']):array()),
                "photo_big" => $row['photo_big'],
                "photo_small" => $row['photo_small'],
                "category" => intval($row['category']),
                "count_dl" => intval($row['dl_count']),
                "file_url" => $row['file_url'],
                "file_size" => $row['file_size'],
                "file_size_in_bytes" => $size_in_bytes ? $size_in_bytes : 0
            );
        }
        return $result;
    }

    protected function getCategoriesMethod() {
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

    private function getCategoryColor($key) {
        if (!empty($key) && preg_match("/^[0-9]$/i", $key)) {
            $colors = array('DDDDDD', '4ABFB4', 'FD5559', 'B8914E', 'B3C833', '9B59B6', 'FF9100', '2ECC71');
            return $colors[($key - 1) % 8];
        }
        return 'CCCCCC';
    }

    protected function addAuthorMethod($params) {
        if ($params['id_edition'] != 0 && $params['author'] != null) {
            $this->db->query(
                "INSERT INTO `help_authors`
                (`id_edition`, `author`, `time`)
                VALUES(?i, ?s, ?i)",
                $params['id_edition'], $params['author'], $params['time']
            );
        } else {
            return array("status" => "Error");
        }
        return array("status" => "OK");
    }

    protected function addEditionMethod($params) {
        if ($params['link'] != null && $params['hash'] != null) {
            $this->db->query(
                "INSERT INTO `edition_offer`
                (`link`, `hash`, `time`, `ip`)
                VALUES(?s, ?s, ?i, ?s)",
                $params['link'], $params['hash'], $params['time'], $params['ip']
            );
        } else {
            return array("status" => "Error");
        }
        return array("status" => "OK");
    }

    protected function addTicketMethod($params) {
        if ($params['message'] != null) {
            $this->db->query(
                "INSERT INTO `tickets`
                (`email`, `ticket_message`, `time`)
                VALUES(?s, ?s, ?i)",
                $params['email'], $params['message'], $params['time']
            );
        } else {
            return array("status" => "Error");
        }
        return array("status" => "OK");
    }

    protected function getSizeInBytes($size_string) {
        if (preg_match("/^\d+(mb|kb)/i", $size_string)) {
            preg_match("/^(\d+)/i", $size_string, $size);
            $size[0] *= (1 << 10);
            if (preg_match("/^\d+mb/i", $size_string)) {
                $size[0] *= (1 << 10);
            }
            return $size[0];
        }
        return !1;
    }
}