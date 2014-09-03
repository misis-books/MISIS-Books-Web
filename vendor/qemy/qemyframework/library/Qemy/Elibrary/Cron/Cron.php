<?php

namespace Qemy\Elibrary\Cron;

use Qemy\Core\Application;
use Qemy\Db\QemyDb;

class Cron {

    private $db;

    const KEY = 'a4b0aece8c8f41b09dc444ae77a7b56a';

    function __construct(QemyDb &$db) {
        $this->db = $db;
    }

    public function createPopularSnapshot($params) {
        $key = $params['key'];
        if ($key == self::KEY) {
            try {
                $dynamic = $this->db->simpleQuery("SELECT * FROM `dynamic_popular`");
                if ($dynamic->num_rows >= 20) {
                    $this->db->simpleQuery("DELETE FROM `static_popular`"); /* truncate issues some errors */
                    while ($row = $dynamic->fetch_array()) {
                        $this->db->query(
                            "INSERT INTO `static_popular`
                            (`id_edition`, `week_dl_count`)
                            VALUES (?i, ?i)",
                            $row['id_edition'],
                            $row['week_dl_count']
                        );
                    }
                    $this->db->simpleQuery("COMMIT");
                    $this->db->simpleQuery("DELETE FROM `dynamic_popular`");
                    $this->db->simpleQuery("COMMIT");
                    echo 'Success commit';
                } else {
                    echo 'Rows < 20';
                }
            } catch (\Exception $err) {
                $this->db->simpleQuery("ROLLBACK");
            }
        } else {
            header('HTTP/1.1 403 Forbidden');
            echo 'Error: Invalid key';
            Application::stop();
        }
    }
}