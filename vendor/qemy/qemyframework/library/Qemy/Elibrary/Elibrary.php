<?php

namespace Qemy\Elibrary;

use Qemy\Db\QemyDatabase;
use Qemy\User\User;

final class Elibrary {

    public $cookie;
    public $success_auth = false;

    public $redirect_url;
    private $db;
    private $user;

    /**
     * @param $db QemyDatabase
     * @param $user User
     */
    function __construct($db, $user) {
        $this->db = $db;
        $this->user = $user;
    }

    public function Auth() {
        $ch = curl_init('http://elibrary.misis.ru/login.php');
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'action' => 'login',
            'cookieverify' => '',
            'redirect' => '',
            'username' => '777777',
            'password' => 'Новотроицк',
            'language' => 'ru_UN'
        ));
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $matches = array();
        preg_match_all("/Set-Cookie: (\S+)/i", $content, $matches);
        $this->cookie = implode(' ', $matches[1]);
        $this->redirect_url = $info['redirect_url'];
        $this->CheckCookie();
    }

    public function CheckCookie() {
        $ch = curl_init($this->redirect_url);
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $this->redirect_url = $info['redirect_url'];
        if ($this->redirect_url == 'http://elibrary.misis.ru/browse.php') {
            $this->success_auth = true;
        }
    }

    public function GetPage($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    public function GetFile($url) {
        if ($this->success_auth) {
            $opts = array('http' => array('header' => 'Cookie: ' . $this->cookie."\r\n"));
            $context = stream_context_create($opts);
            return file_get_contents($url, false, $context);
        }
        return false;
    }

    public function SaveFile($dir, $content) {
        file_put_contents($dir, $content);
    }

    public static function CreatePreviewDir($file_name, $sub) {
        if (!empty($file_name)) {
            return Q_PATH."/../s.twosphere.ru/previews/".$sub."/".$file_name.".jpg";
        }
        return "./../../s.twosphere.ru/previews/small/".rand(1,1000000).".jpg";
    }

    public static function CreateFileDir($file_name) {
        if (!empty($file_name)) {
            return Q_PATH."/../s.twosphere.ru/doc/".Elibrary::NameEncoding($file_name).".pdf";
        }
        return "/static/docs/".rand(10000000,100000000).".pdf";
    }

    private static function ToTranslit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }

    public static function NameEncoding($name) {
        $name = Elibrary::ToTranslit($name);
        $name = strtolower($name);
        $name = preg_replace('/№/', '-', $name);
        $name = preg_replace('~[^-a-z0-9_]+~u', '-', $name);
        $name = trim($name, "-");
        return $name;
    }

    public function GetFileByHash($hash) {
        if (!empty($hash)) {
            $hash = addslashes((string)$hash);
            $res = $this->db->simpleQuery("SELECT *,
                                        MATCH (`hash`)
                                        AGAINST ('+".$hash."' IN BOOLEAN MODE) as REL
                                        FROM `editions`
                                        WHERE MATCH (`hash`) AGAINST ('+".$hash."' IN BOOLEAN MODE)");
            if ($res->num_rows > 0) {
                return $res->fetch_assoc();
            } else {
                echo 'Такого документа не существует';
                exit();
            }
        } else {
            echo 'Такого документа не существует';
            exit();
        }
    }

    public function CheckFileExist($row) {
        if (!empty($row['file_url'])) {
            return true;
        }
        return false;
    }

    public function ShowFile($hash) {
        if (!empty($hash) && $this->user->isAuth() && $this->user->hasSubscription()) {
            $file_in_db = $this->GetFileByHash($hash);
            $this->RefreshStats($file_in_db, $this->user);
            if ($this->CheckFileExist($file_in_db)) {
                $url = $file_in_db['file_url'].'?hash=ef3f91ff4';

                preg_match("/s.twosphere.ru\/(.*)/i", $url, $matches);
                $filename = Q_PATH."/../s.twosphere.ru/".$matches[1];

                header("Location: $url");
            } else {
                $this->Auth();
                if ($this->success_auth) {
                    $file = $this->GetFile($this->CreateDocUrl($file_in_db['doc_id']));
                    $this->SavePreviewByDocId($file_in_db['doc_id']);
                    $name = $file_in_db['name'];
                    header("Content-type: application/pdf");
                    header("Content-Disposition: attachment; filename=$name.pdf");
                    $dir = Elibrary::CreateFileDir($name);
                    $this->SaveFile($dir, $file);
                    $edition_id = $file_in_db['id'];
                    $file_url = 'http://s.twosphere.ru/doc/'.Elibrary::NameEncoding($name).'.pdf';
                    $this->db->query("UPDATE editions SET file_url=?s WHERE `id` = ?i", $file_url, $edition_id);
                    echo $file;
                    unset($file);
                } else {
                    echo '<p style="font-size: 16px; font-family: Tahoma;">На <b>elibrary.misis.ru</b> большие проблемы. Очень. Все пропало. Спасайтесь, глупцы.<br>Файл «'.$file_in_db['name'].'», возможно, никогда не увидит свет.</p>';
                    exit();
                }
            }
        } else {
            header("Location: http://twosphere.ru");
            exit();
        }
    }

    private function CreateDocUrl($doc_id) {
        if (!empty($doc_id)) {
            return "http://elibrary.misis.ru/plugins/SecView/getDoc.php?doc=".$doc_id;
        } else {
            echo 'Произошла неизвестная ошибка, обратитесь к Администратору. <a href="http://twosphere.ru/support">Он здесь.</a>';
            exit();
        }
    }

    private function CreateHash($size) {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $hash = "";
        for ($i = 0; $i < $size; ++$i)
            $hash .= $str[rand(0, strlen($str) - 1)];
        return $hash;
    }

    public function Parse() {
        $this->Auth();
        $url = "";

        $html = $this->GetPage("http://elibrary.misis.ru/browse.php?fFolderId=61&page_size=350");

        //?fFolderId=566">1995</a>
        $pattern = "/(\d+)..(\d+)\-(\d+)/";
        $matches = array();
        preg_match_all($pattern, $html, $matches);
        $folders = $matches[1];
        for ($i = 0; $i < count($folders); ++$i) {
            echo $folders[$i] . "<br>";
            $url = "http://elibrary.misis.ru/browse.php?fFolderId=" . $folders[$i] . "&page_size=350";
            $html = $this->GetPage($url);
            $matches_2 = array();
            $pattern = "/<td\sclass[=]\"browse_column\s*\">(\D*)\</";
            preg_match_all($pattern, $html, $matches_2);
            $array = array();
            $k = 2;
            foreach ($matches_2 as $key => $value) {
                if ($key != 1) continue;
                for ($j = 0; $j < count($value); ++$j) {
                    $temp = trim(str_replace(array('</td>', ','), array('', ''), $value[$j]));
                    if ($j == $k) {
                        $array[] = $temp;
                        $k += 3;
                    } else {
                        continue;
                    }
                }
            }

            $doc_id_array = array();
            $names = array();

            $matches_2 = array();
            $pattern = "/(\d+)\"\stitle[=]\".*\".(..*)/";
            preg_match_all($pattern, $html, $matches_2);
            for ($j = 0; $j < count($matches_2[1]); ++$j) {
                $doc_id_array[] = $matches_2[1][$j];
                $names[] = $matches_2[2][$j];
            }

            $struct_id_author = array(
                "doc_id" => array(),
                "author" => array(),
                "name" => array()
            );
            foreach ($array as $key => $value) {
                //echo $key . " => " . $value . " -> doc_id = " . $doc_id_array[$key] . " -> name = " . $names[$key] . "<br>";
                $struct_id_author['author'][$key] = $array[$key];
                $struct_id_author['doc_id'][$key] = $doc_id_array[$key];
                $struct_id_author['name'][$key] = $names[$key];
            }

            for ($j = 0; $j < count($struct_id_author['doc_id']); ++$j) {
                $doc_id = intval($struct_id_author['doc_id'][$j]);
                $author = $struct_id_author['author'][$j];

                $name = trim(str_replace(array('</a>', '</div>', '&nbsp;', '&laquo;', '&raquo;', '&lt;', '&gt;', '&ldquo;', '&rdquo;', '&times;', '&Agrave;'), array('', '', ' ', '«', '»', '<', '>', '“', '”', '×', 'A'), $struct_id_author['name'][$j]));
                preg_match_all("/\((\d+(Mb|Kb))\)$/i", $name, $file_size_matches);
                $file_size = $file_size_matches[1][0];
                $name = trim(preg_replace("/(\s*\(\d+(Mb|Kb)\))$/i", '', $name));

                $hash = $this->CreateHash(32);
                $dl_url = "http://twosphere.ru/doc/?id=" . $hash;

                if (!empty($author) && !empty($doc_id)) {
                    $res = $this->db->query("SELECT * FROM `editions` WHERE `doc_id` = ?i", $doc_id);
                    if ($res->num_rows == 0) {
                        $this->db->simpleQuery("INSERT INTO `editions` (doc_id, name, hash, dl_url, author, category, file_size) VALUES ($doc_id, '$name', '$hash', '$dl_url', '$author', 2, '$file_size')");
                    }
                }

                echo $file_size . ' | ' . $doc_id . ' | ' . htmlspecialchars($name) . ' | ' . $author . '<br>';
            }
            echo " - Success.<br><br><br>";
        }
    }


    /**
     * @param $edition
     * @param $user User
     */
    private function RefreshStats($edition, $user) {
        $user->incrementDownloadCount();
        $this->db->query(
            "INSERT INTO dynamic_popular
            (id_edition, week_dl_count)
            VALUES (?i, 1)
            ON DUPLICATE KEY
            UPDATE week_dl_count = week_dl_count + 1",
            $edition['id']
        );
        $this->db->simpleQuery("COMMIT");

        $this->db->query("UPDATE `editions` SET dl_count = dl_count + 1 WHERE `id` = ?i", $edition['id']);
        $time = time();
        $query = $edition['name']." | IP: ".$_SERVER['REMOTE_ADDR'];
        $uid = -1;
        $num_book = $edition['id'];
        $this->db->simpleQuery('SET CHARACTER SET latin1');
        $this->db->simpleQuery('SET NAMES latin1');
        $this->db->query("INSERT INTO `elib_dl_list` (uid, query, num_book, time, user_id) VALUES(?i, ?s, ?i, ?i, ?i)", $uid, $query, $num_book, $time, $user->getId());
        $this->db->simpleQuery('SET CHARACTER SET utf8');
        $this->db->simpleQuery('SET NAMES utf8');
    }

    public function ParseDiplomas() {
        try {
            $this->Auth();
            $url = "";
            if (!empty($_GET['doc'])) {
                $html = $this->GetPage("http://elibrary.misis.ru/browse.php?fFolderId=".$_GET['doc']."&page_size=413");

                //?fFolderId=566">1995</a>
                $pattern = "/[?]fFolderId[=](\d+)\".\d+</";
                $matches = array();
                preg_match_all($pattern, $html, $matches);
                $folders = $matches[1];
                for ($i = 0; $i < count($folders); ++$i) {
                    echo $folders[$i]."<br>";
                    $url = "http://elibrary.misis.ru/browse.php?fFolderId=".$folders[$i]."&page_size=350";
                    $html = $this->GetPage($url);
                    $matches_2 = array();
                    $pattern = "/<td\sclass[=]\"browse_column\s*\">(\D*)\</";
                    preg_match_all($pattern, $html, $matches_2);
                    $array = array();
                    $k = 2;
                    foreach ($matches_2 as $key => $value) {
                        if ($key != 1) continue;
                        for ($j = 0; $j < count($value); ++$j) {
                            $temp = trim(str_replace(array('</td>', ','), array('', ''), $value[$j]));
                            if ($j == $k) {
                                $array[] = $temp;
                                $k += 3;
                            } else {
                                continue;
                            }
                        }
                    }

                    $doc_id_array = array();
                    $names = array();

                    $matches_2 = array();
                    $pattern = "/(\d+)\"\stitle[=]\".*\".(..*)/";
                    preg_match_all($pattern, $html, $matches_2);
                    for ($j = 0; $j < count($matches_2[1]); ++$j) {
                        $doc_id_array[] = $matches_2[1][$j];
                        $names[] = $matches_2[2][$j];
                    }

                    $struct_id_author = array(
                        "doc_id" => array(),
                        "author" => array(),
                        "name" => array()
                    );
                    foreach ($array as $key => $value) {
                        echo $key." => ".$value." -> doc_id = ".$doc_id_array[$key]." -> name = ".$names[$key]."<br>";
                        $struct_id_author['author'][$key] = $array[$key];
                        $struct_id_author['doc_id'][$key] = $doc_id_array[$key];
                        $struct_id_author['name'][$key] = $names[$key];
                    }

                    for ($j = 0; $j < count($struct_id_author['doc_id']); ++$j) {
                        $doc_id = intval($struct_id_author['doc_id'][$j]);
                        $author = $struct_id_author['author'][$j];
                        $name = $struct_id_author['name'][$j];
                        $name = str_replace(array('</a>','</div>', '&nbsp;'), array('','', ' '), $name);
                        $hash = $this->CreateHash(32);
                        $dl_url = "http://twosphere.ru/doc/?id=".$hash;
                        if (!empty($author) && !empty($doc_id)) {
                            $res = $this->db->simpleQuery("SELECT * FROM `editions` WHERE `doc_id` = $doc_id");
                            if ($res->num_rows == 0) {
                                $this->db->simpleQuery("INSERT INTO `editions` (doc_id, name, hash, dl_url, author, category) VALUES ($doc_id, '$name', '$hash', '$dl_url', '$author', 3)");
                            }
                        }
                    }
                    echo " - Success.<br><br><br>";
                }
            }
        } catch(\Exception $err) {
            echo $err->getMessage();
        }
    }

    public function ParseCollections() {
        try {
            $this->Auth();
            $url = "http://elibrary.misis.ru/browse.php?fFolderId=342&page_size=500";
            $html = $this->GetPage($url);
            $matches_2 = array();
            $pattern = "/<td\sclass[=]\"browse_column\s*\">(\D*)\</";
            preg_match_all($pattern, $html, $matches_2);
            $array = array();
            $k = 2;
            foreach ($matches_2 as $key => $value) {
                if ($key != 1) continue;
                for ($j = 0; $j < count($value); ++$j) {
                    $temp = trim(str_replace(array('</td>', ','), array('', ''), $value[$j]));
                    if ($j == $k) {
                        $array[] = $temp;
                        $k += 3;
                    } else {
                        continue;
                    }
                }
            }

            $doc_id_array = array();
            $names = array();

            $matches_2 = array();
            $pattern = "/(\d+)\"\stitle[=]\".*\".(..*)/";
            preg_match_all($pattern, $html, $matches_2);
            for ($j = 0; $j < count($matches_2[1]); ++$j) {
                $doc_id_array[] = $matches_2[1][$j];
                $names[] = $matches_2[2][$j];
            }

            $struct_id_author = array(
                "doc_id" => array(),
                "author" => array(),
                "name" => array()
            );
            foreach ($array as $key => $value) {
                echo $key." => ".$value." -> doc_id = ".$doc_id_array[$key]." -> name = ".$names[$key]."<br>";
                $struct_id_author['author'][$key] = $array[$key];
                $struct_id_author['doc_id'][$key] = $doc_id_array[$key];
                $struct_id_author['name'][$key] = $names[$key];
            }

            for ($j = 0; $j < count($struct_id_author['doc_id']); ++$j) {
                $doc_id = intval($struct_id_author['doc_id'][$j]);
                $author = $struct_id_author['author'][$j];
                $name = $struct_id_author['name'][$j];
                $name = str_replace(array('</a>','</div>', '&nbsp;'), array('','', ' '), $name);
                $hash = $this->CreateHash(32);
                $dl_url = "http://twosphere.ru/doc/?id=".$hash;
                $author = str_replace(array('</a>','</div>','&nbsp;','&nbsp','МИСиС','МИС','ИС','МИСИС'), array(''), $author);
                $author = trim($author);
                if (!empty($author) && !empty($doc_id)) {
                    $res = $this->db->simpleQuery("SELECT * FROM `editions` WHERE `doc_id` = $doc_id");
                    if ($res->num_rows == 0) {
                        $this->db->simpleQuery("INSERT INTO `editions` (doc_id, name, hash, dl_url, author, category) VALUES ($doc_id, '$name', '$hash', '$dl_url', '$author', 4)");
                    }
                }
            }
            echo " - Success.<br><br><br>";
        } catch(\Exception $err) {
            echo $err->getMessage();
        }
    }

    public function ParseMonograms() {
        try {
            $this->Auth();
            $url = "http://elibrary.misis.ru/browse.php?fFolderId=824&page_size=500";
            $html = $this->GetPage($url);
            $matches_2 = array();
            $pattern = "/<td\sclass[=]\"browse_column\s*\">(\D*)\</";
            preg_match_all($pattern, $html, $matches_2);
            $array = array();
            $k = 2;
            foreach ($matches_2 as $key => $value) {
                if ($key != 1) continue;
                for ($j = 0; $j < count($value); ++$j) {
                    $temp = trim(str_replace(array('</td>', ','), array('', ''), $value[$j]));
                    if ($j == $k) {
                        $array[] = $temp;
                        $k += 3;
                    } else {
                        continue;
                    }
                }
            }

            $doc_id_array = array();
            $names = array();

            $matches_2 = array();
            $pattern = "/(\d+)\"\stitle[=]\".*\".(..*)/";
            preg_match_all($pattern, $html, $matches_2);
            for ($j = 0; $j < count($matches_2[1]); ++$j) {
                $doc_id_array[] = $matches_2[1][$j];
                $names[] = $matches_2[2][$j];
            }

            $struct_id_author = array(
                "doc_id" => array(),
                "author" => array(),
                "name" => array()
            );
            foreach ($array as $key => $value) {
                echo $key." => ".$value." -> doc_id = ".$doc_id_array[$key]." -> name = ".$names[$key]."<br>";
                $struct_id_author['author'][$key] = $array[$key];
                $struct_id_author['doc_id'][$key] = $doc_id_array[$key];
                $struct_id_author['name'][$key] = $names[$key];
            }

            for ($j = 0; $j < count($struct_id_author['doc_id']); ++$j) {
                $doc_id = intval($struct_id_author['doc_id'][$j]);
                $author = $struct_id_author['author'][$j];
                $name = $struct_id_author['name'][$j];
                $name = str_replace(array('</a>','</div>', '&nbsp;'), array('','', ' '), $name);
                $hash = $this->CreateHash(32);
                $dl_url = "http://twosphere.ru/doc/?id=".$hash;
                $author = str_replace(array('</a>','</div>','&nbsp;','&nbsp','МИСиС','МИС','ИС','МИСИС',','), array(''), $author);
                $author = trim($author);
                if (!empty($author) && !empty($doc_id)) {
                    $res = $this->db->simpleQuery("SELECT * FROM `editions` WHERE `doc_id` = $doc_id");
                    if ($res->num_rows == 0) {
                        $this->db->simpleQuery("INSERT INTO `editions` (doc_id, name, hash, dl_url, author, category) VALUES ($doc_id, '$name', '$hash', '$dl_url', '$author', 5)");
                    }
                }
            }
            echo " - Success.<br><br><br>";
        } catch(\Exception $err) {
            echo $err->getMessage();
        }
    }

    public function ParseBooksMisis() {
        try {
            $this->Auth();
            $url = "http://elibrary.misis.ru/browse.php?fFolderId=96&page_size=500";
            $html = $this->GetPage($url);
            $matches_2 = array();
            $pattern = "/<td\sclass[=]\"browse_column\s*\">(\D*)\</";
            preg_match_all($pattern, $html, $matches_2);
            $array = array();
            $k = 2;
            foreach ($matches_2 as $key => $value) {
                if ($key != 1) continue;
                for ($j = 0; $j < count($value); ++$j) {
                    $temp = trim(str_replace(array('</td>', ','), array('', ''), $value[$j]));
                    if ($j == $k) {
                        $array[] = $temp;
                        $k += 3;
                    } else {
                        continue;
                    }
                }
            }

            $doc_id_array = array();
            $names = array();

            $matches_2 = array();
            $pattern = "/(\d+)\"\stitle[=]\".*\".(..*)/";
            preg_match_all($pattern, $html, $matches_2);
            for ($j = 0; $j < count($matches_2[1]); ++$j) {
                $doc_id_array[] = $matches_2[1][$j];
                $names[] = $matches_2[2][$j];
            }

            $struct_id_author = array(
                "doc_id" => array(),
                "author" => array(),
                "name" => array()
            );
            foreach ($array as $key => $value) {
                echo $key." => ".$value." -> doc_id = ".$doc_id_array[$key]." -> name = ".$names[$key]."<br>";
                $struct_id_author['author'][$key] = $array[$key];
                $struct_id_author['doc_id'][$key] = $doc_id_array[$key];
                $struct_id_author['name'][$key] = $names[$key];
            }

            for ($j = 0; $j < count($struct_id_author['doc_id']); ++$j) {
                $doc_id = intval($struct_id_author['doc_id'][$j]);
                $author = $struct_id_author['author'][$j];
                $name = $struct_id_author['name'][$j];
                $name = str_replace(array('</a>','</div>', '&nbsp;'), array('','', ' '), $name);
                $hash = $this->CreateHash(32);
                $dl_url = "http://twosphere.ru/doc/?id=".$hash;
                $author = str_replace(array('</a>','</div>','&nbsp;','&nbsp','МИСиС','МИС','ИС','МИСИС',','), array(''), $author);
                $author = trim($author);
                if (!empty($doc_id)) {
                    $res = $this->db->simpleQuery("SELECT * FROM `editions` WHERE `doc_id` = $doc_id");
                    if ($res->num_rows == 0) {
                        $this->db->simpleQuery("INSERT INTO `editions` (doc_id, name, hash, dl_url, author, category) VALUES ($doc_id, '$name', '$hash', '$dl_url', '$author', 6)");
                    }
                }
            }
            echo " - Success.<br><br><br>";
        } catch(\Exception $err) {
            echo $err->getMessage();
        }
    }

    public function ParsePhysics() {
        try {
            $this->Auth();
            $url = "http://elibrary.misis.ru/browse.php?fFolderId=192&page_size=500";
            $html = $this->GetPage($url);
            $matches_2 = array();
            $pattern = "/<td\sclass[=]\"browse_column\s*\">(\D*)\</";
            preg_match_all($pattern, $html, $matches_2);
            $array = array();
            $k = 2;
            foreach ($matches_2 as $key => $value) {
                if ($key != 1) continue;
                for ($j = 0; $j < count($value); ++$j) {
                    $temp = trim(str_replace(array('</td>', ','), array('', ''), $value[$j]));
                    if ($j == $k) {
                        $array[] = $temp;
                        $k += 3;
                    } else {
                        continue;
                    }
                }
            }

            $doc_id_array = array();
            $names = array();

            $matches_2 = array();
            $pattern = "/(\d+)\"\stitle[=]\".*\".(..*)/";
            preg_match_all($pattern, $html, $matches_2);
            for ($j = 0; $j < count($matches_2[1]); ++$j) {
                $doc_id_array[] = $matches_2[1][$j];
                $names[] = $matches_2[2][$j];
            }

            $struct_id_author = array(
                "doc_id" => array(),
                "author" => array(),
                "name" => array()
            );
            foreach ($array as $key => $value) {
                echo $key." => ".$value." -> doc_id = ".$doc_id_array[$key]." -> name = ".$names[$key]."<br>";
                $struct_id_author['author'][$key] = $array[$key];
                $struct_id_author['doc_id'][$key] = $doc_id_array[$key];
                $struct_id_author['name'][$key] = $names[$key];
            }

            for ($j = 0; $j < count($struct_id_author['doc_id']); ++$j) {
                $doc_id = intval($struct_id_author['doc_id'][$j]);
                $author = $struct_id_author['author'][$j];
                $name = $struct_id_author['name'][$j];
                $name = str_replace(array('</a>','</div>', '&nbsp;'), array('','', ' '), $name);
                $hash = $this->CreateHash(32);
                $dl_url = "http://twosphere.ru/doc/?id=".$hash;
                $author = str_replace(array('</a>','</div>','&nbsp;','&nbsp','МИСиС','МИС','ИС','МИСИС',',','и др.'), array(''), $author);
                $author = trim($author);
                if (!empty($doc_id)) {
                    $res = $this->db->simpleQuery("SELECT * FROM `editions` WHERE `doc_id` = $doc_id");
                    if ($res->num_rows == 0) {
                        $this->db->simpleQuery("INSERT INTO `editions` (doc_id, name, hash, dl_url, author, category) VALUES ($doc_id, '$name', '$hash', '$dl_url', '$author', 8)");
                    }
                }
            }
            echo " - Success.<br><br><br>";
        } catch(\Exception $err) {
            echo $err->getMessage();
        }
    }

    public function ParseDissertations() {
        try {
            $this->Auth();
            $url = "";
            if (!empty($_GET['doc'])) {
                $html = $this->GetPage("http://elibrary.misis.ru/browse.php?fFolderId=".$_GET['doc']."&page_size=413");

                //?fFolderId=566">1995</a>
                $pattern = "/[?]fFolderId[=](\d+)\".\d+</";
                $matches = array();
                preg_match_all($pattern, $html, $matches);
                $folders = $matches[1];
                for ($i = 0; $i < count($folders); ++$i) {
                    echo $folders[$i]."<br>";
                    $url = "http://elibrary.misis.ru/browse.php?fFolderId=".$folders[$i]."&page_size=350";
                    $html = $this->GetPage($url);
                    $matches_2 = array();
                    $pattern = "/<td\sclass[=]\"browse_column\s*\">(\D*)\</";
                    preg_match_all($pattern, $html, $matches_2);
                    $array = array();
                    $k = 2;
                    foreach ($matches_2 as $key => $value) {
                        if ($key != 1) continue;
                        for ($j = 0; $j < count($value); ++$j) {
                            $temp = trim(str_replace(array('</td>', ','), array('', ''), $value[$j]));
                            if ($j == $k) {
                                $array[] = $temp;
                                $k += 3;
                            } else {
                                continue;
                            }
                        }
                    }

                    $doc_id_array = array();
                    $names = array();

                    $matches_2 = array();
                    $pattern = "/(\d+)\"\stitle[=]\".*\".(..*)/";
                    preg_match_all($pattern, $html, $matches_2);
                    for ($j = 0; $j < count($matches_2[1]); ++$j) {
                        $doc_id_array[] = $matches_2[1][$j];
                        $names[] = $matches_2[2][$j];
                    }

                    $struct_id_author = array(
                        "doc_id" => array(),
                        "author" => array(),
                        "name" => array()
                    );
                    foreach ($array as $key => $value) {
                        echo $key." => ".$value." -> doc_id = ".$doc_id_array[$key]." -> name = ".$names[$key]."<br>";
                        $struct_id_author['author'][$key] = $array[$key];
                        $struct_id_author['doc_id'][$key] = $doc_id_array[$key];
                        $struct_id_author['name'][$key] = $names[$key];
                    }

                    for ($j = 0; $j < count($struct_id_author['doc_id']); ++$j) {
                        $doc_id = intval($struct_id_author['doc_id'][$j]);
                        $author = $struct_id_author['author'][$j];
                        $name = $struct_id_author['name'][$j];
                        $name = str_replace(array('</a>','</div>', '&nbsp;'), array('','', ' '), $name);
                        $hash = $this->CreateHash(32);
                        $dl_url = "http://twosphere.ru/doc/?id=".$hash;
                        $author = str_replace(array('</a>','</div>','&nbsp;','&nbsp','МИСиС','МИС','ИС','МИСИС',',','и др.'), array(''), $author);
                        $author = trim($author);
                        if (!empty($doc_id)) {
                            $res = $this->db->simpleQuery("SELECT * FROM `editions` WHERE `doc_id` = $doc_id");
                            if ($res->num_rows == 0) {
                                $this->db->simpleQuery("INSERT INTO `editions` (doc_id, name, hash, dl_url, author, category) VALUES ($doc_id, '$name', '$hash', '$dl_url', '$author', 7)");
                            }
                        }
                    }
                    echo " - Success.<br><br><br>";
                }
            }
        } catch(Exception $err) {
            echo $err->getMessage();
        }
    }

    public function TrimData() {
        $res = $this->db->query("SELECT * FROM `editions`");
        while ($row = $res->fetch_array()) {
            $name = trim($row['name']);
            $this->db->query("UPDATE `editions` SET `name` = ?s WHERE `id` = ?i", $name, $row['id']);
        }
        echo 'success!';
    }

    private function CreatePreviewName() {
        return md5($this->CreateHash(64).time()).mb_strtolower($this->CreateHash(16));
    }

    public function SavePreviews($offset, $count) {
        $this->Auth();
        if ($this->success_auth) {
            $res = $this->db->query("SELECT * FROM `editions` LIMIT ?i, ?i", $offset, $count);
            if (!$res->num_rows) {
                echo 'err';
                return;
            }
            while ($row = $res->fetch_array()) {
                var_dump($row);
                $doc_id = $row['doc_id'];
                $id = $row['id'];
                //http://elibrary.misis.ru/plugins/SecView/getDoc.php?id=[doc_id]&page=0&type=large/fast
                //http://elibrary.misis.ru/plugins/SecView/getDoc.php?id=[doc_id]&page=0&type=small/fast
                //http://s.twosphere.ru/previews/small/
                //http://s.twosphere.ru/previews/large/
                $name = $this->CreatePreviewName();

                $dir_small = $this->CreatePreviewDir($name, "small");
                $dir_large = $this->CreatePreviewDir($name, "large");

                $this->SaveFile($dir_small, $this->GetFile("http://elibrary.misis.ru/plugins/SecView/getDoc.php?id=".$doc_id."&page=0&type=small/fast"));
                $this->SaveFile($dir_large, $this->GetFile("http://elibrary.misis.ru/plugins/SecView/getDoc.php?id=".$doc_id."&page=0&type=large/fast"));

                $url_small = "http://s.twosphere.ru/previews/small/".$name.".jpg";
                $url_large = "http://s.twosphere.ru/previews/large/".$name.".jpg";

                $this->db->query("UPDATE `editions` SET `photo_small` = ?s, `photo_big` = ?s WHERE `id` = ?i", $url_small, $url_large, $id);
            }
            echo 'success';
        }
    }

    public function SavePreviewByDocId($doc_id) {
        $this->Auth();
        if ($this->success_auth) {
            $res = $this->db->query("SELECT * FROM `editions` WHERE doc_id = ?i", $doc_id);
            if (!$res->num_rows) {
                return;
            }
            while ($row = $res->fetch_array()) {
                $doc_id = $row['doc_id'];
                $id = $row['id'];
                //http://elibrary.misis.ru/plugins/SecView/getDoc.php?id=[doc_id]&page=0&type=large/fast
                //http://elibrary.misis.ru/plugins/SecView/getDoc.php?id=[doc_id]&page=0&type=small/fast
                //http://s.twosphere.ru/previews/small/
                //http://s.twosphere.ru/previews/large/
                $name = $this->CreatePreviewName();

                $dir_small = $this->CreatePreviewDir($name, "small");
                $dir_large = $this->CreatePreviewDir($name, "large");

                //Warning! Большие картинки больше не сохраняются
                $this->SaveFile($dir_small, $this->GetFile("http://elibrary.misis.ru/plugins/SecView/getDoc.php?id=".$doc_id."&page=0&type=small/fast"));
                //$this->SaveFile($dir_large, $this->GetFile("http://elibrary.misis.ru/plugins/SecView/getDoc.php?id=".$doc_id."&page=0&type=large/fast"));

                $url_small = "http://s.twosphere.ru/previews/small/".$name.".jpg";
                //$url_large = "http://s.twosphere.ru/previews/large/".$name.".jpg";
                $url_large = "http://s.twosphere.ru/previews/small/".$name.".jpg";

                $this->db->query("UPDATE `editions` SET `photo_small` = ?s, `photo_big` = ?s WHERE `id` = ?i", $url_small, $url_large, $id);
            }
        }
    }

    public function ShowError($message) {
        echo '<style type="text/css">';
        echo 'a { text-decoration: none; flex-wrap: nowrap; white-space: nowrap; padding: 8px 20px; display: inline-block; cursor: pointer; margin: 0; }
        .default-button { font-size: 14px; background: #FD5559; background: linear-gradient(to bottom, rgba(255, 64, 65, 0.73), #FD5559); color: #FFF; outline: none; -webkit-border-radius: 2px; -moz-border-radius: 2px; border-radius: 2px; border: 1px solid #FD5559; }
        .default-button:disabled { background: rgba(253, 85, 89, 0.62); border: 1px solid rgba(253, 85, 89, 0.87); } .default-button:disabled:hover { background: rgba(253, 85, 89, 0.62); } .default-button:disabled:active { background: rgba(253, 85, 89, 0.62); } .default-button:hover { background: linear-gradient(to bottom, rgba(255, 64, 65, 0.73), rgba(253, 85, 89, 0.89)); } .default-button:active { background: linear-gradient(to top, rgba(255, 64, 65, 0.76), #ff5f64); }';
        echo '</style>';
        echo '<div style="text-align: center; font-family: Arial; font-size: 30px; padding: 40px; 20px;">';
        echo $message;
        echo '<br><br><a onclick="window.close();" class="default-button" href="/">Вернуться назад</a>';
        echo '</div>';
        exit();
    }
}