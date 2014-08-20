<?php

namespace Qemy\Api;

use Qemy\Db\QemyDb;

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

                        $query = addslashes((string)trim($this->params['request']['q']));
                        $category = (!empty($this->params['request']['category'])) ? intval($this->params['request']['category']) : 1;
                        $count = (!empty($this->params['request']['count'])) ? intval($this->params['request']['count']) : 10;
                        $offset = (!empty($this->params['request']['offset'])) ? intval($this->params['request']['offset']) : 0;

                        if ($category < 1 || $category > 8) {
                            $this->category = 1;
                        }
                        if ($count < 1 || $count > 200) {
                            $count = 10;
                        }
                        if ($offset < 0) {
                            $offset = 0;
                        }

                        $response = array(
                            "response" => array(
                                "emptyQuery" => strlen($query) == 0,
                                "items" => array(),
                                "category" => array(
                                    "key" => $category,
                                    "value" => $this->GetCategoryName($category)
                                )
                            )
                        );

                        if ($response['response']['emptyQuery']) {
                            return $response;
                        }

                        $res = null; $res_count = null;
                        $res_count = $this->ResonableSearchCount($query, $category);
                        if (!$res_count->num_rows) {
                            $res = $this->SearchQuery($query, $category, $offset, $count);
                            $res_count = $this->SearchResultCount($query, $category);
                        } else {
                            $res = $this->ResonableSearchQuery($query, $category, $offset, $count);
                        }

                        $found_count = $res_count->num_rows;
                        if (!$found_count) {
                            $response['response']['found'] = false;
                            $response['response']['text'] = 'По запросу «'.str_replace(array('\\'), array(''), $query).'» ничего не найдено.';
                            $response['response']['items_count'] = 0;
                            $response['response']['all_items_count'] = 0;
                            $response['response']['query'] = $query;
                            return $response;
                        } else {
                            $this->AddToStats($query, $time, $_SERVER['REMOTE_ADDR']);
                            $offset_found_count = ($found_count - $offset < $count) ? $found_count - $offset : $count;
                            $response['response']['found'] = true;
                            $response['response']['text'] = $this->SpecifyCount($found_count);
                            $response['response']['items_count'] = ($offset_found_count < 0) ? 0 : $offset_found_count;
                            $response['response']['all_items_count'] = $found_count;
                            $response['response']['query'] = $query;
                            $index = 1;
                            while($row = $res->fetch_assoc()) {
                                $response['response']['items'][] = array(
                                    "index" => $index++,
                                    "id" => intval($row['id']),
                                    "name" => $this->ToUtf(strip_tags($row['name'])),
                                    "download_url" => $row['dl_url'],
                                    "file_url" => $row['file_url'],
                                    "file_size" => $row['file_size'],
                                    "photo_big" => $row['photo_big'],
                                    "photo_small" => $row['photo_small'],
                                    "authors" => ((!empty($row['author']))?explode(',', $row['author']):array()),
                                    "category" => intval($row['category']),
                                    "count_dl" => intval($row['dl_count'])
                                );
                            }
                        }
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

    private function SearchResultCount($query, $category) {
        $category = ($category != 1) ? "`category` = $category AND" : "";
        return $this->db->simpleQuery("SELECT *
                                    FROM `editions`
                                    WHERE $category `name` LIKE '%$query%'");
    }

    private function SearchQuery($query, $category, $offset, $count) {
        $category = ($category != 1) ? "`category` = $category AND" : "";
        return $this->db->simpleQuery("SELECT *
                                    FROM `editions`
                                    WHERE $category `name` LIKE '%$query%'
                                    ORDER BY `dl_count` DESC
                                    LIMIT $offset, $count");
    }

    private function ResonableSearchCount($query, $category) {
        $category = ($category != 1) ? "`category` = $category AND" : "";
        return $this->db->simpleQuery("SELECT *,
                                    MATCH (`name`, `author`)
                                    AGAINST ('+".$query."' IN BOOLEAN MODE) as REL
                                    FROM `editions`
                                    WHERE $category MATCH (`name`, `author`) AGAINST ('+".$query."' IN BOOLEAN MODE)
                                    ORDER BY REL DESC");
    }

    private function ResonableSearchQuery($query, $category, $offset, $count) {
        $category = ($category != 1) ? "`category` = $category AND" : "";
        return $this->db->simpleQuery("SELECT *,
                                    MATCH (`name`, `author`)
                                    AGAINST ('+".$query."' IN BOOLEAN MODE) as REL
                                    FROM `editions`
                                    WHERE $category MATCH (`name`, `author`) AGAINST ('+".$query."' IN BOOLEAN MODE)
                                    ORDER BY REL DESC
                                    LIMIT $offset, $count");
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

                        if (is_numeric($category) && $category < 1 || $category > 8) {
                            $this->category = 1;
                        }
                        if ($count < 1 || $count > 200) {
                            $count = 10;
                        }

                        $response = array(
                            "response" => array(
                                "items" => array()
                            )
                        );

                        if ($category == 1)
                            $res = $this->db->query("SELECT * FROM `editions` ORDER BY `dl_count` DESC LIMIT 0, ?i", $count);
                        else
                            $res = $this->db->query("SELECT * FROM `editions` WHERE `category` = ?i ORDER BY `dl_count` DESC LIMIT 0, ?i", $category, $count);

                        $found_count = $res->num_rows;
                        $response['response']['items_count'] = $found_count;
                        $response['response']['category'] = array(
                            "key" => $category,
                            "value" => $this->GetCategoryName($category)
                        );
                        $index = 1;
                        while($row = $res->fetch_assoc()) {
                            $response['response']['items'][] = array(
                                "index" => $index++,
                                "id" => intval($row['id']),
                                "name" => $this->ToUtf(strip_tags($row['name'])),
                                "download_url" => $row['dl_url'],
                                "file_url" => $row['file_url'],
                                "file_size" => $row['file_size'],
                                "photo_big" => $row['photo_big'],
                                "photo_small" => $row['photo_small'],
                                "authors" => ((!empty($row['author']))?explode(',', $row['author']):array()),
                                "category" => intval($row['category']),
                                "count_dl" => intval($row['dl_count'])
                            );
                        }
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
                    return 'Монографии, научные издания';
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
                        $response['response']['items_count'] = self::CATEGORIES_COUNT;
                        for ($i = 1; $i <= self::CATEGORIES_COUNT; ++$i) {
                            $response['response']['categories'][] = array(
                                "key" => $i,
                                "category_name" => $this->GetCategoryName($i),
                                "color_hex" => $this->GetCategoryColor($i)
                            );
                        }
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

    private function AddToStats($query, $time, $ip, $api = 1) {
        $this->db->query("INSERT INTO `queries_stats` (`query`, `time`, `ip`, `api`) VALUES(?s, ?i, ?s, ?i)", (string)$query, intval($time), (string)$ip, $api);
    }
}