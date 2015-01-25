<?php

namespace Qemy\Elibrary\Methods\Documents;

use Qemy\Db\QemyDatabase;

abstract class AbstractDocuments {

    /** @var $db QemyDatabase */
    private $db;

    public static $EMPTY_PHOTO_URL = 'http://twosphere.ru/st/img/not-found-image.png';

    function __construct(&$db) {
        $this->db = $db;
    }

    protected function getExtSearchResultsAll($query, $category) {
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

    protected function getSearchResults($query, $category, $count, $offset, $user_id = 1) {
        $category = ($category != 1) ? "`category` = $category AND" : "";
        return $this->db->simpleQuery(
            "SELECT editions.*, faves.*
            FROM `editions`
            LEFT JOIN faves ON faves.edition_id = editions.id AND faves.user_id = $user_id
            WHERE $category (`name` LIKE '%$query%' OR `author` LIKE '%$query%')
            ORDER BY `dl_count` DESC
            LIMIT $offset, $count"
        );
    }

    protected function getSearchResultsAll($query, $category) {
        $category = ($category != 1) ? "`category` = $category AND" : "";
        return $this->db->simpleQuery(
            "SELECT *
            FROM `editions`
            WHERE $category `name` LIKE '%$query%'"
        );
    }

    protected function getExtSearchResults($query, $category, $count, $offset, $user_id = 1) {
        $category = ($category != 1) ? "editions.`category` = $category AND" : "";
        return $this->db->simpleQuery(
            "SELECT faves.*,editions.*,
            MATCH (editions.`name`, editions.`author`)
            AGAINST ('+".$query."' IN BOOLEAN MODE) as REL
            FROM `editions`
            LEFT JOIN faves ON faves.edition_id = editions.id AND faves.user_id = $user_id
            WHERE $category MATCH (editions.`name`, editions.`author`) AGAINST ('+".$query."' IN BOOLEAN MODE)
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

    protected function searchQuery($query, $category, $count, $offset, $user_id) {
        $res = null; $res_count = null;
        $res_count = $this->getExtSearchResultsAll($query, $category);
        if (!$res_count->num_rows) {
            $res = $this->getSearchResults($query, $category, $count, $offset, $user_id);
            $res_count = $this->getSearchResultsAll($query, $category);
        } else {
            $res = $this->getExtSearchResults($query, $category, $count, $offset, $user_id);
        }
        return array(
            $res,
            $res_count
        );
    }

    protected function searchSpecifyCount($count) {
        $word = "документ";
        $array_of_suf_unique = ['', 'a', 'ов'];
        $array_of_suf_dozen = ['ов', '', 'а', 'а', 'а', 'ов', 'ов', 'ов', 'ов', 'ов'];
        $array_of_suf_find = ['', 'о'];
        $mod = $count % 100;
        if ($mod >= 11 && $mod <= 14) {
            return 'Найден'.$array_of_suf_find[1].' '.$count.' '.$word.$array_of_suf_unique[2];
        } else {
            $mod %= 10;
            return 'Найден'.$array_of_suf_find[(($mod == 1) ? 0 : 1)].' '.$count.' '.$word.$array_of_suf_dozen[$mod];
        }
    }

    protected function toUtf($str) {
        return str_replace(array('&laquo;', '&raquo;', '&lt;', '&gt;', '&ldquo;', '&rdquo;', '&times;', '&Agrave;'), array('«', '»', '<', '>', '“', '”', '×', 'A'), $str);
    }

    protected function getCategoryName($key) {
        if (!empty($key) && preg_match("/^[0-9]$/i", $key)) {
            $categories = array('Все', 'Пособия', 'Дипломы', 'Сборники научных трудов', 'Монографии', 'Книги МИСиС', 'Авторефераты диссертаций', 'Разное');
            return $categories[($key - 1) % 8];
        }
        return 'Не определено';
    }

    protected function realEscapeString($text) {
        return $this->db->real_escape_string($text);
    }

    protected function getCategoryColor($key) {
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
            $this->sendNotification("Добавлен новый автор");
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
            $this->sendNotification("Добавлен новый документ");
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
            $this->sendNotification("Добавлен новый тикет: ".$params['message']);
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

    protected function sendNotification($message) {
        $to      = 'ipritoflex@yandex.ru';
        $subject = $message;
        $headers = 'From: admin@twosphere.ru' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }

    protected function getPhotoWithCheck($photo_url) {
        return empty($photo_url) ? self::$EMPTY_PHOTO_URL : $photo_url;
    }

    /**
     * @param $mysqli_result \mysqli_result
     * @param $fields string
     */
    protected function getItemsWithFields($mysqli_result, $fields) {
        $default_fields = array(
            'id',
            'name'
        );
        $compact_fields = array(
            'id',
            'name',
            'download_url',
            'authors',
            'category',
            'size',
            'fave'
        );
        $all_fields = array(
            'id',
            'name',
            'download_url',
            'authors',
            'photo_big',
            'photo_small',
            'category',
            'count_dl',
            'size',
            'count_dl_week',
            'fave'
        );
        $cur_fields = explode(',', $fields);
        $result_fields = array();
        $result_fields += $default_fields;

        if (in_array('all', $cur_fields)) {
            $result_fields = array_unique(array_merge($result_fields, $all_fields));
        }

        if (in_array('compact', $cur_fields)) {
            $result_fields = array_unique(array_merge($result_fields, $compact_fields));
        }

        $allowed_cur_fields = array();
        foreach ($cur_fields as $field) {
            if (in_array($field, $all_fields)) {
                array_push($allowed_cur_fields, $field);
            }
        }
        $result_fields = array_unique(array_merge($result_fields, $allowed_cur_fields));

        $items = array();
        while ($row = $mysqli_result->fetch_assoc()) {
            $result_item = array();
            if (in_array('id', $result_fields)) {
                $result_item['id'] = intval($row['id']);
            }
            if (in_array('name', $result_fields)) {
                $result_item['name'] = $this->toUtf(strip_tags($row['name']));
            }
            if (in_array('download_url', $result_fields)) {
                $result_item['download_url'] = $row['dl_url'];
            }
            if (in_array('authors', $result_fields)) {
                $result_item['authors'] = !empty($row['author']) ? explode(',', $row['author']) : array();
            }
            if (in_array('photo_big', $result_fields)) {
                $result_item['photo_big'] = $this->getPhotoWithCheck($row['photo_big']);
            }
            if (in_array('photo_small', $result_fields)) {
                $result_item['photo_small'] = $this->getPhotoWithCheck($row['photo_small']);
            }
            if (in_array('category', $result_fields)) {
                $result_item['category'] = array(
                    'id' => intval($row['category']),
                    'name' => $this->getCategoryName(intval($row['category']))
                );
            }
            if (in_array('count_dl', $result_fields)) {
                $result_item['count_dl'] = intval($row['dl_count']);
            }
            if (in_array('size', $result_fields)) {
                $result_item['size'] = $row['file_size'];
            }
            if (in_array('count_dl_week', $result_fields) && isset($row['week_dl_count'])) {
                $result_item['count_dl_week'] = intval($row['week_dl_count']);
            }
            if (in_array('fave', $result_fields)) {
                $result_item['fave'] = !empty($row['user_id']);
            }
            array_push($items, $result_item);
        }
        return $items;
    }

    protected function addStats($user_id, $query, $time, $ip, $api = 0) {
        if (!empty($query)) {
            $this->db->query("INSERT INTO `queries_stats` (user_id, `query`, `time`, `ip`, `api`) VALUES(?i, ?s, ?i, ?s, ?i)", $user_id, (string)$query, intval($time), (string)$ip, $api);
        }
    }
}