<?php
/** @var $this \Qemy\Core\View\View */
?>
<div class="container">
    <div class="content_layout">
        <div class="content_wrapper">
            <?php
            if (isset($_POST['submit'])) {
                $_SESSION['adm_pass'] = base64_encode($_POST['password']);
            }
            if (isset($_POST['exit'])) {
                unset($_SESSION['adm_pass']);
            }

            if ($_SESSION['adm_pass'] != 'MTE1NTYzYQ==') {
                ?>
                <form method="post">
                    <div style="margin: 0 auto; width: 50%;">
                        <input id="pass" class="input_text_default" placeholder="Пароль" style="margin-top: 10px;" name="password" type="password">
                        <input name="submit" class="popup_submit" style="margin-top: 10px;" type="submit" value="Принять">
                    </div>
                </form>
            <?php
            } else {
                ?>
                <form method="post">
                    <input name="exit" class="popup_submit" style="margin-top: 10px;" type="submit" value="Выйти">
                </form>
                <style>
                    .container_update {
                        border: 1px solid #777;
                        border-radius: 10px;
                        padding: 20px;
                        margin-top: 10px;
                        text-align: center;
                        max-height: 600px;
                        overflow-y: scroll;
                    }
                    .adm_req_result_item {
                        padding: 5px;
                        border: 1px solid #000;
                        margin-top: 3px;
                        color: #fff;
                        width: 377px;
                        margin-right: 4px;
                        display: inline-block;
                        background: #58b2ff;
                        transition: background 25s ease-in;
                        -moz-transition: background 25s ease-in;
                        -webkit-transition: background 25s ease-in;
                        -o-transition: background 25s ease-in;
                    }
                </style>
                <div class="search_wrapper" style="margin-top: 20px;">
                    <div class="search_layout">
                        Запросы в реальном времени:<br>
                        <div class="container_update"></div>
                    </div>
                </div>
                <div class="search_wrapper">
                    <div class="search_layout">
                        Новые авторы:<br>
                        <?php
                        while ($row = $this->getData()['authors']->fetch_array()) {
                            echo $row[0].") doc_id: ".$row[1]." | author: ".$row[2]." | timestamp: ".date("d.m.Y ", $row[3])." | active: ".$row[4]."<br>";
                        }
                        ?>
                    </div>
                </div>
                <div class="search_wrapper">
                    <div class="search_layout">
                        Новые тикеты:<br>
                        <?php
                        while ($row = $this->getData()['tickets']->fetch_array()) {
                            echo $row[0].") email: ".$row[1]."<br>ticket:<br><b>".$row[2]."</b><br>timestamp: ".date("d.m.Y", $row[3])." | active: ".$row[4]."<br><br><br>";
                        }
                        ?>
                    </div>
                </div>
                <div class="search_wrapper">
                    <div class="search_layout">
                        Новые материалы:<br>
                        <?php
                        while ($row = $this->getData()['materials']->fetch_array()) {
                            echo $row[0].") link: ".$row[1]."<br>hash: ".$row[2]."<br>timestamp: ".date("d.m.Y", $row[3])."<br>ip: ".$row[4]."<br><br><br>";
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <?php
    $this->includeView('footer');
    ?>
</div>
<?php
$this->includeModuleView('add_edition');
?>