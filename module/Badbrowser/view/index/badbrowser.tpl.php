<div class="container">
    <div class="content_layout">
        <div class="content_wrapper">
            <style type="text/css">
                .container_advice,
                .container_browsers
                {
                    width: 92%;
                    margin: 0 auto;
                }

                .container_browsers
                {
                    padding: 10px 15px;
                }

                .item_browser
                {
                    display: inline-block;
                    padding: 10px;
                    text-decoration: none;
                    color: #4F6173;
                    width: 123px;
                }

                .item_browser:hover
                {
                    background: rgba(177, 218, 255, 0.26);
                    -webkit-border-radius: 4px;
                    -moz-border-radius: 4px;
                    border-radius: 4px;
                }

                .clear_fix
                {
                    display: block;
                }

                .container {
                    padding: 30px 0 0;
                }
            </style>
            <div class="container" style="padding-bottom: 0;">
                <div class="content_layout" style="min-height: 0; padding-bottom: 0; background: transparent; width: 900px;">
                    <div class="notification_message" style="padding: 40px 0 20px; width: 500px; margin: 0 auto; text-align: center; margin-bottom: 0px;">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABAAgMAAADXB5lNAAAACVBMVEVTU1P///9TU1P8g2f9AAAAAnRSTlMAAHaTzTgAAAB0SURBVHhe1dMxCgMxDAXRIaVOkXrvs/f79ZwyEGNITKwUgcBOZR4upEKcSxeCmSrAFko1r5Cv4J8A6EGzgwLQQAdHD5ljbUFU08Gz8AOwgWX1HlBNC6WyBVWMOBpq5C2pz3ALUIGsP1ZAKtznmxoAAtc5wgegV/QjpyQHeQAAAABJRU5ErkJggg==">
                        <span style="text-align: center; padding: 20px 0; display: block;">Ваш браузер устарел :C</span>
                        <div class="notification_message" style="background: #DDEBF4; background: rgba(177, 218, 255, 0.26); padding: 20px; width: 84%; margin: 0 auto 10px;">Для работы с сайтом необходима поддержка <nobr>Local Storage</nobr>, Cookies, Javascript. Мы сожалеем, но Ваш браузер не поддерживает одну или несколько из этих технологий.</div>
                        <span class="container_advice clear_fix" style="margin-bottom: 10px; margin-top: 16px;">Воспользуйтесь одним из этих современных браузеров:</span>
                        <div class="container_browsers notification_message" style="-moz-box-shadow: 0 0 0 rgba(0,0,0,0.1);-ms-box-shadow: 0 0 0 rgba(0,0,0,0.1);-webkit-box-shadow: 0 0 0 rgba(0,0,0,0.1);box-shadow: 0 0 0 rgba(0,0,0,0.1);">
                            <a target="_blank" href="http://browser.yandex.ru/" class="item_browser"><img width="65%" src="http://twosphere.ru/st/img/yandex_browser.png"><div style="padding-top: 10px;">Яндекс.Браузер</div></a>
                            <a target="_blank" href="http://google.com/chrome" class="item_browser"><img src="http://vk.com/images/chrome.png?1"><div style="padding-top: 10px;">Chrome</div></a>
                            <a target="_blank" href="http://www.mozilla-europe.org/" class="item_browser"><img src="http://vk.com/images/firefox.png?1"><div style="padding-top: 10px;">Mozilla Firefox</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $this->includeView('footer');
    ?>
</div>
<?php
$this->includeModuleView('add_edition');
?>