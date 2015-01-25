<?php

namespace Qemy\User;

use Qemy\Db\QemyDatabase;
use Qemy\User\Authorization\CheckAuthorization;

class User implements UserInterface {

    /**
     * @var $db QemyDatabase
     */
    private $db;
    /**
     * @var $table_row array
     */
    private $table_row;
    /**
     * @var $check_auth CheckAuthorization
     */
    private $check_auth;

    const ACCESS_KEY_COUNT_LIMITER = 3;
    const NO_PHOTO = 'http://twosphere.ru/st/img/no_photo.png';

    function __construct($db, $row = null) {
        $this->db = $db;
        $this->table_row = $row;
    }

    public function setTableRow($row) {
        $this->table_row = $row;
    }

    public function allocateUserByVkId($vk_id) {
        if (is_numeric($vk_id)) {
            $result = $this->db->query("SELECT * FROM `users` WHERE `vk_id` = ?i", $vk_id);
            $this->setTableRow($result->num_rows ? $result->fetch_array(MYSQL_ASSOC) : null);
        }
    }

    public function allocateUserById($id) {
        if (is_numeric($id)) {
            $result = $this->db->query("SELECT * FROM `users` WHERE `id` = ?i", $id);
            $this->setTableRow($result->num_rows ? $result->fetch_array(MYSQL_ASSOC) : null);
        }
    }

    public function getObject() {
        return $this->table_row;
    }

    public function setCheckAuthorization(CheckAuthorization& $class) {
        $this->check_auth = $class;
        if ($this->isAuth()) {
            $this->updateRecentActivtyTime();
        }
    }

    public function isAuth() {
        return $this->check_auth && $this->table_row ? $this->check_auth->getResult() : false;
    }

    public function isEmpty() {
        return empty($this->table_row);
    }

    public function getId() {
        return $this->table_row['id'];
    }

    public function getVkId() {
        return $this->table_row['vk_id'];
    }

    public function getAccessLevel() {
        $access_level = !empty($this->table_row['access_level']) ? $this->table_row['access_level'] : 0;
        if ($this->isAuth() && !$this->hasSubscription()) {
            $access_level--;
        }
        return $access_level;
    }

    public function getFirstName() {
        return $this->table_row['first_name'];
    }

    public function getLastName() {
        return $this->table_row['last_name'];
    }

    public function getViewName() {
        return $this->getFirstName().' '.$this->getLastName();
    }

    public function getVkProfileReference() {
        return 'https://vk.com/id'.$this->getVkId();
    }

    public function getPhoto() {
        return $this->table_row['photo'] && $this->table_row['photo'] != 'undefined' ? $this->table_row['photo'] : self::NO_PHOTO;
    }

    public function getRegisterTime() {
        return !empty($this->table_row['register_time']) ? $this->table_row['register_time'] : time();
    }

    public function getRegisterDate() {
        return date("d.m.Y в H:i", $this->getRegisterTime());
    }

    public function getDownloadCount() {
        return $this->table_row['dl_count'];
    }

    public function getQueriesCount() {
        return $this->table_row['count_queries'];
    }

    public function getLastLoggedTime() {
        return !empty($this->table_row['last_logged_time']) ? $this->table_row['last_logged_time'] : time();
    }

    public function getLastLoggedDate() {
        return date("d.m.Y в H:i", $this->getLastLoggedTime());
    }

    public function getLastLoggedEllapsed() {
        if ($this->getLastLoggedTime()) {
            return $this->formatEllapsedTime($this->getLastLoggedTime());
        }
        return "1 минуту назад";
    }

    public function getAccessKeys() {
        return !empty($this->table_row['access_keys']) ? $this->table_row['access_keys'] : "";
    }

    public function getAccessKeysArray() {
        $keys = $this->getAccessKeys();
        if (!$keys) {
            return array();
        }
        return explode(',', $keys);
    }

    public function accessKeyExists($target_key) {
        $keys = $this->getAccessKeysArray();
        if (!count($keys)) {
            return !1;
        }
        return in_array($target_key, $keys);
    }

    public function addAccessKey($new_key) {
        $cur_keys = $this->getAccessKeysArray();
        if ($this->accessKeyExists($new_key)) {
            return;
        }
        while (count($cur_keys) >= self::ACCESS_KEY_COUNT_LIMITER) {
            array_shift($cur_keys);
        }
        array_push($cur_keys, $new_key);
        $this->accessKeysCommit($cur_keys);
    }

    public function deleteAccessKey($target_key) {
        $cur_keys = $this->getAccessKeysArray();
        if (!$this->accessKeyExists($target_key)) {
            return;
        }
        for ($i = 0; $i < count($cur_keys); ++$i) {
            if ($target_key == $cur_keys[$i]) {
                unset($cur_keys[$i]);
                break;
            }
        }
        $cur_keys = array_values($cur_keys);
        $this->accessKeysCommit($cur_keys);
    }

    private function accessKeysArraySerialize($keys_array) {
        if (!count($keys_array)) {
            return "";
        }
        return implode(',', $keys_array);
    }

    private function accessKeysCommit($cur_keys) {
        $this->table_row['access_keys'] = $this->accessKeysArraySerialize($cur_keys);
        $this->db->query("UPDATE `users` SET `access_keys` = ?s WHERE `id` = ?i", (string)$this->getAccessKeys(), intval($this->getId()));
        $this->db->query("COMMIT");
    }

    public function getRecentActivityTime() {
        return !empty($this->table_row['recent_activity_time']) ? intval($this->table_row['recent_activity_time']) : time();
    }

    public function getRecentActivityDate() {
        return date("d.m.Y в H:i", $this->getRecentActivityTime());
    }

    public function getRecentActivityEllapsed() {
        $time = $this->getRecentActivityTime();
        return $this->formatEllapsedTime($time);
    }

    public function isOnline() {
        $recent_activity_time = $this->getRecentActivityTime();
        $cur_time = time();
        $offset = $cur_time - $recent_activity_time;
        return $offset < 300 && count($this->getAccessKeysArray());
    }

    public function setAccessLevel($level) {
        $user_id = $this->getId();
        if (!is_numeric($user_id)) {
            return;
        }
        $this->db->query("UPDATE `users` SET `access_level` = ?i WHERE `id` = ?i", $level, $user_id);
        $this->db->query("COMMIT");
        $this->table_row['access_level'] = $level;
    }

    public function setFirstName($first_name) {
        $user_id = $this->getId();
        if (!is_numeric($user_id)) {
            return;
        }
        $this->db->query("UPDATE `users` SET `first_name` = ?s WHERE `id` = ?i", $first_name, $user_id);
        $this->db->query("COMMIT");
        $this->table_row['first_name'] = $first_name;
    }

    public function setLastName($last_name) {
        $user_id = $this->getId();
        if (!is_numeric($user_id)) {
            return;
        }
        $this->db->query("UPDATE `users` SET `last_name` = ?s WHERE `id` = ?i", $last_name, $user_id);
        $this->db->query("COMMIT");
        $this->table_row['last_name'] = $last_name;
    }

    public function setVkProfileDomain($domain) {
        $user_id = $this->getId();
        if (!is_numeric($user_id)) {
            return;
        }
        $this->db->query("UPDATE `users` SET `vk_domain` = ?s WHERE `id` = ?i", $domain, $user_id);
        $this->db->query("COMMIT");
        $this->table_row['vk_domain'] = $domain;
    }

    public function setPhoto($url) {
        $user_id = $this->getId();
        if (!is_numeric($user_id)) {
            return;
        }
        $this->db->query("UPDATE `users` SET `photo` = ?s WHERE `id` = ?i", $url, $user_id);
        $this->db->query("COMMIT");
        $this->table_row['photo'] = $url;
    }

    public function incrementDownloadCount() {
        $user_id = $this->getId();
        if (!is_numeric($user_id)) {
            return;
        }
        $this->db->query("UPDATE `users` SET `dl_count` = `dl_count` + 1 WHERE `id` = ?i", $user_id);
        $this->db->query("COMMIT");
        $this->table_row['dl_count']++;
    }

    public function incrementQueriesCount() {
        $user_id = $this->getId();
        if (!is_numeric($user_id)) {
            return;
        }
        $this->db->query("UPDATE `users` SET `count_queries` = `count_queries` + 1 WHERE `id` = ?i", $user_id);
        $this->db->query("COMMIT");
        $this->table_row['count_queries']++;
    }

    public function updateLastLoggedTime() {
        $user_id = $this->getId();
        $cur_time = time();
        if (!is_numeric($user_id)) {
            return;
        }
        $this->db->query("UPDATE `users` SET `last_logged_time` = ?i WHERE `id` = ?i", $cur_time, $user_id);
        $this->db->query("COMMIT");
        $this->table_row['last_logged_time'] = $cur_time;
    }

    public function updateRecentActivtyTime() {
        $user_id = $this->getId();
        $cur_time = time();
        if (!is_numeric($user_id)) {
            return;
        }
        $this->db->query("UPDATE `users` SET `recent_activity_time` = ?i WHERE `id` = ?i", $cur_time, $user_id);
        $this->db->query("COMMIT");
        $this->table_row['recent_activity_time'] = $cur_time;
    }

    private function formatEllapsedTime($time) {
        $cur_time = time();
        $diff = $cur_time - $time;
        if ($diff <= 0) {
            return '1 минуту назад';
        }
        $seconds = $diff;
        $minutes = intval($seconds / 60);
        $hours = intval($minutes / 60);
        if ($seconds < 60) {
            return $seconds.' '.$this->wordSeconds($seconds).' назад';
        }
        if ($minutes < 60) {
            return $minutes.' '.$this->wordMinutes($minutes).' назад';
        }
        if ($hours < 12) {
            return $hours.' '.$this->wordHours($hours).' назад';
        }
        return date("d.m.Y в H:i", $time);
    }

    private function wordSeconds($seconds) {
        $word = "секунд";
        $array_of_suf_unique = ['', 'у', 'ы'];
        $array_of_suf_dozen = ['', 'у', 'ы', 'ы', 'ы', '', '', '', '', ''];
        $mod = $seconds % 100;
        if ($mod >= 11 && $mod <= 14) {
            return $word.$array_of_suf_unique[0];
        } else {
            $mod %= 10;
            return $word.$array_of_suf_dozen[$mod];
        }
    }

    private function wordMinutes($minutes) {
        $word = "минут";
        $array_of_suf_unique = ['', 'у', 'ы'];
        $array_of_suf_dozen = ['', 'у', 'ы', 'ы', 'ы', '', '', '', '', ''];
        $mod = $minutes % 100;
        if ($mod >= 11 && $mod <= 14) {
            return $word.$array_of_suf_unique[0];
        } else {
            $mod %= 10;
            return $word.$array_of_suf_dozen[$mod];
        }
    }

    private function wordHours($hours) {
        $word = "час";
        $array_of_suf_unique = ['', 'а', 'ов'];
        $array_of_suf_dozen = ['ов', '', 'а', 'а', 'а', 'ов', 'ов', 'ов', 'ов', 'ов'];
        $mod = $hours % 100;
        if ($mod >= 11 && $mod <= 14) {
            return $word.$array_of_suf_unique[2];
        } else {
            $mod %= 10;
            return $word.$array_of_suf_dozen[$mod];
        }
    }

    private function wordDays($days) {
        $words = ['дней', 'день', 'дня'];
        $mod = $days % 100;
        if ($mod >= 11 && $mod <= 14) {
            return $words[0];
        } else {
            $mod %= 10;
            if ($mod == 1) {
                return $words[1];
            }
            return $mod >= 2 && $mod <= 4 ? $words[2] : $words[0];
        }
    }

    public function getYearsPostfix($years) {
        $words = ['лет', 'год', 'года'];
        $mod = $years % 100;
        if ($mod >= 11 && $mod <= 14) {
            return $words[0];
        } else {
            $mod %= 10;
            if ($mod == 1) {
                return $words[1];
            }
            return $mod >= 2 && $mod <= 4 ? $words[2] : $words[0];
        }
    }

    public function getSubscriptionEndTime() {
        return $this->table_row['end_subscription_time'];
    }

    public function getRemainingSubscriptionTime() {
        $end_time = $this->getSubscriptionEndTime();
        $diff = $end_time - time();
        return $diff < 0 ? 0 : $diff;
    }

    public function getRemainingSubscriptionDays() {
        $remaining_time = $this->getRemainingSubscriptionTime();
        $day = 60 * 60 * 24;
        return intval($remaining_time / $day) + ($remaining_time % $day ? 1 : 0);
    }

    public function getRemainingSubscriptionViewDays() {
        $days = $this->getRemainingSubscriptionDays();
        return $days.' '.$this->wordDays($days);
    }

    public function getRemainingSubcriptionViewF() {
        $rem_days = $this->getRemainingSubscriptionDays();
        $rem_years = intval($rem_days / 365);
        if ($rem_years < 1) {
            $remaining_word = $rem_days % 10 == 1 && !($rem_days % 100 == 11) ? 'Остался' : 'Осталось';
            return $remaining_word.' '.$rem_days.' '.$this->wordDays($rem_days);
        }
        $rem_mod_days = $rem_days % 365;
        $remaining_word = $rem_years % 10 == 1 && !($rem_years % 100 == 11) ? 'Остался' : 'Осталось';
        $year_postfix = $this->getYearsPostfix($rem_years);
        return $remaining_word.' '.$rem_years.' '.$year_postfix.(!$rem_mod_days ? '' : ' и '.$rem_mod_days.' '.$this->wordDays($rem_mod_days));
    }

    public function addDaysToSubscription($days) {
        if (!is_numeric($days) && $this->isAuth()) {
            return !1;
        }
        $remaining_time = $this->getRemainingSubscriptionTime();
        $remaining_time += $days * 60 * 60 * 24;
        $end_subscription_time = time() + $remaining_time;

        $this->db->query(
            "UPDATE `users` SET `end_subscription_time` = ?i WHERE `id` = ?i",
            $end_subscription_time,
            $this->getId()
        );
        $this->db->query("COMMIT");
        $this->table_row['end_subscription_time'] = $end_subscription_time;
        return !0;
    }

    public function hasSubscription() {
        return $this->getRemainingSubscriptionDays() > 0;
    }

    public function getFullInfo() {
        return array(
            'user' => array(
                'id' => $this->getId(),
                'first_name' => $this->getFirstName(),
                'last_name' => $this->getLastName(),
                'view_name' => $this->getViewName(),
                'vk_id' => $this->getVkId(),
                'vk_profile' => $this->getVkProfileReference(),
                'photo' => $this->getPhoto(),
                'register_date' => $this->getRegisterDate(),
                'count_dl' => $this->getDownloadCount(),
                'count_queries' => $this->getQueriesCount(),
                'subscription' => array(
                    'enabled' => $this->hasSubscription(),
                    'end_time' => $this->getSubscriptionEndTime(),
                    'remaining' => array(
                        'days' => $this->getRemainingSubscriptionDays(),
                        'seconds' => $this->getRemainingSubscriptionTime(),
                        'view_days' => $this->getRemainingSubscriptionViewDays(),
                        'formatted' => $this->getRemainingSubcriptionViewF()
                    )
                )
            )
        );
    }
}