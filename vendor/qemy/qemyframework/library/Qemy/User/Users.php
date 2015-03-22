<?php

namespace Qemy\User;

use Qemy\Core\Application;

class Users {

    private $db;

    private $user_row;

    const START_SUBSCRIPTION_PERIOD = 172800;

    /**
     * @param $db \Qemy\Db\QemyDatabase
     */
    function __construct(&$db) {
        $this->db = $db;
    }

    public function isVkUserExists($vk_id) {
        $result = $this->db->query("SELECT * FROM `users` WHERE `vk_id` = ?i", $vk_id);
        $this->user_row = $result->num_rows ? $result->fetch_assoc() : null;
        return $result->num_rows != 0;
    }

    public function getUser() {
        return new User($this->db, $this->user_row);
    }

    public function createUser($user_object) {
        $cur_time = time();
        $this->db->query(
            "INSERT INTO `users` (`vk_id`, `first_name`, `last_name`, `vk_domain`, `photo`, `register_time`, `recent_activity_time`, `end_subscription_time`, `last_logged_time`)
            VALUES (?i, ?s, ?s, ?s, ?s, ?i, ?i, ?i, ?i)",
            $user_object['id'],
            $user_object['first_name'],
            $user_object['last_name'],
            $user_object['domain'],
            $user_object['photo'],
            $cur_time,
            $cur_time,
            $cur_time + self::START_SUBSCRIPTION_PERIOD,
            $cur_time
        );
        $this->db->query("COMMIT");
        return true;
    }

    public function getUsersWithSubscription() {
        $result = $this->db->query("SELECT * FROM `users` WHERE end_subscription_time > ?i", time());
        $list = array();
        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            if ($row['photo'] == 'http://vk.com/images/camera_200.gif') {
                continue;
            }
            $list[$row['id']] = new User($this->db, $row);
        }
        return $list;
    }

    public function getUsersWithSubscriptionNormal($sort, $sort_type) {
        $sort_kind = array(
            'id' => 'id',
            'recent' => 'recent_activity_time',
            'sub' => 'end_subscription_time',
            'q' => 'count_queries',
            'dl' => 'dl_count',
            'default' => 'end_subscription_time'
        );
        $sort_types = array(
            'asc' => 'ASC',
            'desc' => 'DESC',
            'default' => 'ASC'
        );

        $sort_kind_cur = isset($sort_kind[$sort]) ? $sort_kind[$sort] : $sort_kind['default'];
        $sort_type_cur = isset($sort_types[$sort_type]) ? $sort_types[$sort_type] : $sort_types['default'];

        $show = Application::$request_variables['get']['show'];

        $result = $this->db->query(
            "SELECT users.*
            FROM `users`
            WHERE users.end_subscription_time > ?i
            ORDER BY users.$sort_kind_cur $sort_type_cur",
            $show == 'all' ? 0 : time()
        );
        $list = array();
        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            $list[] = new User($this->db, $row);
        }
        return $list;
    }
}