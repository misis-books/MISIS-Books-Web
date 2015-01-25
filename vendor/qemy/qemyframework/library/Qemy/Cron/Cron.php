<?php

namespace Qemy\Cron;

use Qemy\Core\Application;
use Qemy\Db\QemyDatabase;

class Cron {

    private $db;

    const KEY = 'e615df4824bc18ee2a4897bcfecc5b1f';

    function __construct(QemyDatabase $db) {
        $this->db = $db;
    }

    public function createPopularSnapshot($params) {
        $key = $params['key'];
        if (md5($key) == self::KEY) {
            try {
                $dynamic = $this->db->simpleQuery("SELECT * FROM `dynamic_popular`");
                if ($dynamic->num_rows >= 40) {
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
                    echo 'Rows < 40';
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