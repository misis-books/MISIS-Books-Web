<?php

namespace Qemy\Elibrary\Methods\Fave;

use Qemy\Db\QemyDatabase;

abstract class AbstractFaves {

    private $db;

    /**
     * @param $db QemyDatabase
     */
    function __construct($db) {
        $this->db = $db;
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
            'size'
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
            'count_dl_week'
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
            array_push($items, $result_item);
        }
        return $items;
    }

    protected function toUtf($str) {
        return str_replace(array('&laquo;', '&raquo;', '&lt;', '&gt;', '&ldquo;', '&rdquo;', '&times;', '&Agrave;'), array('«', '»', '<', '>', '“', '”', '×', 'A'), $str);
    }

    protected function getPhotoWithCheck($photo_url) {
        return empty($photo_url) ? self::$EMPTY_PHOTO_URL : $photo_url;
    }
}