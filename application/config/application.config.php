<?php

return array(
    'modules' => array(
        '404' => 'Err404',
        'index' => 'Index',
        'fave' => 'Fave',
        'support' => 'Support',
        'methods' => 'Methods',
        'jsonp_methods' => 'CrossdomainMethods',
        'api' => 'Api',
        'dev' => 'Dev',
        'doc' => 'Doc',
        'admin' => 'Admin',
        'yandex' => 'YandexTabloid',
        'badbrowser' => 'Badbrowser',
        'cron_create_popular' => 'Cron',
        'help' => 'Donate'
    ),
    'module_options' => array(
        'module_path' => '/module'
    ),
    'vendor_options' => array(
        'vendor_path' => '/vendor',
        'projects' => array(
            'qemy' => array(
                'base_path' => '/qemy/qemyframework',
                'library_path' => '/qemy/qemyframework/library'
            )
        )
    ),
    'views_options' => array(
        'views_path' => '/vendor/qemy/qemyframework/resources/layout',
        'common_includes' => array(
            'title' => 'MISIS Books',
            'meta' => array(
                'charset' => '<meta http-equiv="content-type" content="text/html; charset=utf-8">',
                'ie' => '<meta http-equiv="X-UA-Compatible" content="IE=edge">',
                'yandex_tabloid' => '<link rel="yandex-tableau-widget" href="/st/yandex/manifest.json">',
                'noscript' => '<noscript><meta http-equiv="refresh" content="0; URL=/badbrowser"></noscript>',
                'yandex.metrika' => '<script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter5843680 = new Ya.Metrika({id:5843680, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/5843680" style="position:absolute; left:-9999px;" alt="" /></div></noscript>'
            ),
            'script' => array(
                'jquery',
                'common',
                'methods',
                'objectstorage',
                'jquery.omodal',
                'jquery.scrollto',
                'modernizr',
                'search'
            ),
            'css' => array(
                'common'
            ),
            'icon' => 'favicon',
            'views' => array(
                'head' => array(
                    'authorized_mode' => false,
                    'path' => 'common/head/head'
                ),
                'footer' => array(
                    'authorized_mode' => false,
                    'path' => 'common/footer/footer'
                )
            )
        )
    ),
    'resource_options' => array(
        'path' => '/application/resources/global.php'
    ),
    'db_options' => require Q_PATH.'/application/config/db.config.php'
);