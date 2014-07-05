<?php

namespace Admin\Model;

use Qemy\Core\Model\AbstractModel;

class UpdModel extends AbstractModel {

    public function main() {
        $timestamp_left = intval($this->getRequestParams()['get']['timestamp_l']);
        $timestamp_right = intval($this->getRequestParams()['get']['timestamp_r']);
        if (empty($timestamp_left) || $timestamp_left > $timestamp_right) {
            $res = $this->getQemyDb()->query("SELECT * FROM `queries_stats` WHERE `time` <= ?i ORDER BY `id` DESC LIMIT 0, ?i", $timestamp_right, 50);
        } else {
            $res = $this->getQemyDb()->query("SELECT * FROM `queries_stats` WHERE `time` > ?i AND `time` <= ?i ORDER BY `id` DESC", $timestamp_left, $timestamp_right);
        }
        $res_container = array();
        $res_container["items_count"] = $res->num_rows;
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            $res_container["items"][] = array(
                "query" => $row['query'],
                "id" => $row['id'],
                "timestamp" => $row['time'],
                "date" => date("H:i:s d.m.Y", $row['time']),
                "api" => $row['api']
            );
        }
        $this->setData($res_container);
        return $this;
    }
}