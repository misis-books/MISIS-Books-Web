<?php

namespace Qemy\Elibrary\Api;

use Qemy\Db\QemyDb;
use Qemy\Elibrary\Methods\Methods;

class Api {

    const DEFAULT_MAX_QUERIES = 15; //count
    const DEFAULT_PERIOD = 5; //seconds
    const DEFAULT_BAN_TIME = 10; //seconds
    const DEFAULT_BAN_TIME_TOKEN = 60; //seconds
    const CATEGORIES_COUNT = 8; //count of categories

    private $db;
    private $params;

    function __construct($params, $db) {
        /** @var $db QemyDb */
        $this->db = $db;
        $this->params = $params;
        $this->SendHeaders();
    }

    private function SendHeaders() {
        header("Cache-Control: Cache-Control:no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        if ($this->params['request']['type'] == 'xml') {
            header("Content-Type: text/xml; charset=utf-8");
        } else {
            header("Content-Type: application/json; charset=utf-8");
        }
    }

    public function RegisterDevice() {
        //регистрация устройства
        $ip = $_SERVER['REMOTE_ADDR'];
        $time = time();
        $search_ip = $this->db->query("SELECT * FROM `api_users` WHERE `creation_time` + ".self::DEFAULT_BAN_TIME_TOKEN." > ?i AND `creation_ip` = ?s", $time, $ip);
        if ($search_ip->num_rows) {
            return array(
                "error" => "too_many_requests",
                "error_description" => "Too many requests for the creation of access_token. Try after 1 minute."
            );
        }
        $access_token = $this->CreateHash();
        $this->db->query("INSERT INTO `api_users` (`access_token`,`creation_time`,`creation_ip`) VALUES (?s, ?i, ?s)", $access_token, $time, $ip);
        return array(
            "response" => array(
                "access_token" => $access_token
            )
        );
    }

    public function Search() {
        //поиск
        $time = time();
        $access_token = (!empty($this->params['request']['access_token'])) ? $this->params['request']['access_token'] : "";
        $query = (!empty($this->params['request']['q'])) ? $this->params['request']['q'] : "";
        $offset = (!empty($this->params['request']['offset'])) ? $this->params['request']['offset'] : "";
        $count = (!empty($this->params['request']['count'])) ? $this->params['request']['count'] : "";
        $category = (!empty($this->params['request']['category'])) ? $this->params['request']['category'] : "";

        $response = array();
        $request_params = array(
            array(
                "key" => "access_token",
                "value" => $access_token
            ),
            array(
                "key" => "q",
                "value" => $query
            ),
            array(
                "key" => "count",
                "value" => $count
            ),
            array(
                "key" => "offset",
                "value" => $offset
            ),
            array(
                "key" => "category",
                "value" => $category
            ),
            array(
                "key" => "method",
                "value" => "materials.search"
            )
        );

        if (!empty($access_token)) {
            $info = $this->GetUser($access_token);
            if ($info->num_rows) {
                $row = $info->fetch_array();

                $id = $row['id'];
                $microtime = microtime(true);
                $recent_activity = $row['recent_activity'];
                $recent_count = $row['recent_count_activity'];
                $ban = $row['ban'];
                $unlock_time = $row['unlock_time'];

                if ($ban && $unlock_time - time() <= 0) {
                    $ban = 0;
                    $this->db->query("UPDATE `api_users` SET `ban` = 0 WHERE `id` = ?i", $id);
                }

                if (!$ban) {
                    if ($recent_activity + self::DEFAULT_PERIOD >= $microtime) {
                        $recent_count++;
                    } else {
                        /* clear */
                        $recent_count = 1;
                        $recent_activity = $microtime;
                        $this->db->query("UPDATE `api_users` SET `recent_activity` = $recent_activity,`recent_count_activity` = 1 WHERE `id` = ?i", $id);
                    }

                    if ($recent_count > self::DEFAULT_MAX_QUERIES) {
                        $ban = !0;
                        $unlock_time = time() + self::DEFAULT_BAN_TIME;
                        $this->db->query("UPDATE `api_users` SET `ban` = 1, `unlock_time` = ?i, `recent_count_activity` = 0 WHERE `id` = ?i", $unlock_time, $id);
                    } else {
                        $this->db->query("UPDATE `api_users` SET `recent_count_activity` = ?i WHERE `id` = ?i", $recent_count, $id);
                    }

                    if (!$ban) {
                        //успех
                        $this->db->query("UPDATE `api_users` SET `count_queries_search` = `count_queries_search` + 1 WHERE `id` = ?i", $id);

                        $params = $this->params['request'];
                        $methods = new Methods($this->db);
                        $response = array(
                            "response" => $methods->getSearchResult($params, 1)
                        );
                        return $response;
                    } else {
                        $response = array(
                            "error" => array(
                                "error_code" => 3,
                                "error_msg" => 'Too many requests. Time to unlock: '.($unlock_time - time()).' seconds.',
                                "time_to_unlock" => $unlock_time - time(),
                                "request_params" => $request_params
                            )
                        );
                    }
                } else {
                    $response = array(
                        "error" => array(
                            "error_code" => 3,
                            "error_msg" => 'Too many requests. Time to unlock: '.($unlock_time - time()).' seconds.',
                            "time_to_unlock" => $unlock_time - time(),
                            "request_params" => $request_params
                        )
                    );
                }
            } else {
                $response = array(
                    "error" => array(
                        "error_code" => 17,
                        "error_msg" => 'Invalid access_token',
                        "request_params" => $request_params
                    )
                );
            }
        } else {
            $response = array(
                "error" => array(
                    "error_code" => 1,
                    "error_msg" => 'One of the parameters specified was missing or invalid: access_token is undefined',
                    "request_params" => $request_params
                )
            );
        }

        return $response;
    }

    private function ToUtf($str) {
        return str_replace(array('&laquo;', '&raquo;'), array('«', '»'), $str);
    }

    private function CreateHash($size = 64) {
        for ($str = 'abcdef0123456789', $i = 0, $hash = ""; $i < $size; ++$i)
            $hash .= $str[rand(0, strlen($str) - 1)];
        return $hash;
    }

    private function GetUser($access_token) {
        if (!empty($access_token)) {
            $access_token = addslashes($access_token);
            return $this->db->query("SELECT *,
                                    MATCH (`access_token`)
                                    AGAINST ('+".$access_token."' IN BOOLEAN MODE) as REL
                                    FROM `api_users`
                                    WHERE MATCH (`access_token`) AGAINST ('+".$access_token."' IN BOOLEAN MODE)");
        }
        return !1;
    }

    public function GetPopular() {
        $time = time();
        $access_token = (!empty($this->params['request']['access_token'])) ? $this->params['request']['access_token'] : "";
        $category = (!empty($this->params['request']['category'])) ? intval($this->params['request']['category']) : 1;
        $count = (!empty($this->params['request']['count'])) ? intval($this->params['request']['count']) : 10;

        $response = array();
        $request_params = array(
            array(
                "key" => "access_token",
                "value" => $access_token
            ),
            array(
                "key" => "count",
                "value" => $count
            ),
            array(
                "key" => "category",
                "value" => $category
            ),
            array(
                "key" => "method",
                "value" => "materials.getPopular"
            )
        );

        if (!empty($access_token)) {
            $info = $this->GetUser($access_token);
            if ($info->num_rows) {
                $row = $info->fetch_array();

                $id = $row['id'];
                $microtime = microtime(true);
                $recent_activity = $row['recent_activity'];
                $recent_count = $row['recent_count_activity'];
                $ban = $row['ban'];
                $unlock_time = $row['unlock_time'];

                if ($ban && $unlock_time - time() <= 0) {
                    $ban = 0;
                    $this->db->query("UPDATE `api_users` SET `ban` = 0 WHERE `id` = ?i", $id);
                }

                if (!$ban) {
                    if ($recent_activity + self::DEFAULT_PERIOD >= $microtime) {
                        $recent_count++;
                    } else {
                        /* clear */
                        $recent_count = 1;
                        $recent_activity = $microtime;
                        $this->db->query("UPDATE `api_users` SET `recent_activity` = $recent_activity,`recent_count_activity` = 1 WHERE `id` = ?i", $id);
                    }

                    if ($recent_count > self::DEFAULT_MAX_QUERIES) {
                        $ban = !0;
                        $unlock_time = time() + self::DEFAULT_BAN_TIME;
                        $this->db->query("UPDATE `api_users` SET `ban` = 1, `unlock_time` = ?i, `recent_count_activity` = 0 WHERE `id` = ?i", $unlock_time, $id);
                    } else {
                        $this->db->query("UPDATE `api_users` SET `recent_count_activity` = ?i WHERE `id` = ?i", $recent_count, $id);
                    }

                    if (!$ban) {
                        //успех
                        $this->db->query("UPDATE `api_users` SET `count_queries_getpopular` = `count_queries_getpopular` + 1 WHERE `id` = ?i", $id);

                        $params = $this->params['request'];
                        $methods = new Methods($this->db);
                        $response = array(
                            "response" => $methods->getPopular($params)
                        );
                        return $response;
                    } else {
                        $response = array(
                            "error" => array(
                                "error_code" => 3,
                                "error_msg" => 'Too many requests. Time to unlock: '.($unlock_time - time()).' seconds.',
                                "time_to_unlock" => $unlock_time - time(),
                                "request_params" => $request_params
                            )
                        );
                    }
                } else {
                    $response = array(
                        "error" => array(
                            "error_code" => 3,
                            "error_msg" => 'Too many requests. Time to unlock: '.($unlock_time - time()).' seconds.',
                            "time_to_unlock" => $unlock_time - time(),
                            "request_params" => $request_params
                        )
                    );
                }
            } else {
                $response = array(
                    "error" => array(
                        "error_code" => 17,
                        "error_msg" => 'Invalid access_token',
                        "request_params" => $request_params
                    )
                );
            }
        } else {
            $response = array(
                "error" => array(
                    "error_code" => 1,
                    "error_msg" => 'One of the parameters specified was missing or invalid: access_token is undefined',
                    "request_params" => $request_params
                )
            );
        }
        return $response;
    }

    private function GetCategoryName($key) {
        if (!empty($key) && preg_match("/^[0-9]$/i", $key)) {
            switch($key) {
                case 1:
                    return 'Все';
                    break;
                case 2:
                    return 'Пособия';
                    break;
                case 3:
                    return 'Дипломы';
                    break;
                case 4:
                    return 'Сборники научных трудов';
                    break;
                case 5:
                    return 'Монографии';
                    break;
                case 6:
                    return 'Книги МИСиС';
                    break;
                case 7:
                    return 'Авторефераты диссертаций';
                    break;
                case 8:
                    return 'Разное';
                    break;
                default:
                    return 'Не определено';
            }
        }
        return 'Не определено';
    }

    private function GetCategoryColor($key) {
        if (!empty($key) && preg_match("/^[0-9]$/i", $key)) {
            switch($key) {
                case 2:
                    return '4ABFB4';
                    break;
                case 3:
                    return 'FD5559';
                    break;
                case 4:
                    return 'B8914E';
                    break;
                case 5:
                    return 'B3C833';
                    break;
                case 6:
                    return '9B59B6';
                    break;
                case 7:
                    return 'FF9100';
                    break;
                case 8:
                    return '2ECC71';
                    break;
                default:
                    return 'EFEFF0';
            }
        }
        return 'Не определено';
    }

    public function GetCategories() {
        $time = time();
        $access_token = (!empty($this->params['request']['access_token'])) ? $this->params['request']['access_token'] : "";

        $response = array();
        $request_params = array(
            array(
                "key" => "access_token",
                "value" => $access_token
            ),
            array(
                "key" => "method",
                "value" => "materials.getCategories"
            )
        );

        if (!empty($access_token)) {
            $info = $this->GetUser($access_token);
            if ($info->num_rows) {
                $row = $info->fetch_array();

                $id = $row['id'];
                $microtime = microtime(true);
                $recent_activity = $row['recent_activity'];
                $recent_count = $row['recent_count_activity'];
                $ban = $row['ban'];
                $unlock_time = $row['unlock_time'];

                if ($ban && $unlock_time - time() <= 0) {
                    $ban = 0;
                    $this->db->query("UPDATE `api_users` SET `ban` = 0 WHERE `id` = ?i", $id);
                }

                if (!$ban) {
                    if ($recent_activity + self::DEFAULT_PERIOD >= $microtime) {
                        $recent_count++;
                    } else {
                        /* clear */
                        $recent_count = 1;
                        $recent_activity = $microtime;
                        $this->db->query("UPDATE `api_users` SET `recent_activity` = $recent_activity,`recent_count_activity` = 1 WHERE `id` = ?i", $id);
                    }

                    if ($recent_count > self::DEFAULT_MAX_QUERIES) {
                        $ban = !0;
                        $unlock_time = time() + self::DEFAULT_BAN_TIME;
                        $this->db->query("UPDATE `api_users` SET `ban` = 1, `unlock_time` = ?i, `recent_count_activity` = 0 WHERE `id` = ?i", $unlock_time, $id);
                    } else {
                        $this->db->query("UPDATE `api_users` SET `recent_count_activity` = ?i WHERE `id` = ?i", $recent_count, $id);
                    }

                    if (!$ban) {
                        //успех
                        $methods = new Methods($this->db);
                        $response = array(
                            "response" => $methods->getCategories()
                        );
                        return $response;
                    } else {
                        $response = array(
                            "error" => array(
                                "error_code" => 3,
                                "error_msg" => 'Too many requests. Time to unlock: '.($unlock_time - time()).' seconds.',
                                "time_to_unlock" => $unlock_time - time(),
                                "request_params" => $request_params
                            )
                        );
                    }
                } else {
                    $response = array(
                        "error" => array(
                            "error_code" => 3,
                            "error_msg" => 'Too many requests. Time to unlock: '.($unlock_time - time()).' seconds.',
                            "time_to_unlock" => $unlock_time - time(),
                            "request_params" => $request_params
                        )
                    );
                }
            } else {
                $response = array(
                    "error" => array(
                        "error_code" => 17,
                        "error_msg" => 'Invalid access_token',
                        "request_params" => $request_params
                    )
                );
            }
        } else {
            $response = array(
                "error" => array(
                    "error_code" => 1,
                    "error_msg" => 'One of the parameters specified was missing or invalid: access_token is undefined',
                    "request_params" => $request_params
                )
            );
        }
        return $response;
    }
}