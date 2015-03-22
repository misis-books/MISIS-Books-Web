<?php

return array(
    'modules' => array(
        '404' => 'Err404',
        'index' => 'Index',
        'auth' => 'Auth',
        'methods' => 'Methods',
        'payment' => 'Payment',
        'static' => 'Template',
        'doc' => 'Doc',
        'yandex' => 'YandexTabloid',
        'cron_create_popular' => 'Cron',
        'api' => 'Api',
        'stats' => 'Stats'
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
                'mobile-web-app-capable' => '<meta name="mobile-web-app-capable" content="yes">',
                'viewport' => '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">',
                'apple-touch-icon' => '<link rel="apple-touch-icon-precomposed" href="/st/img/preview-misis-books-4_0.png">',
                'apple-capable' => '<meta name="apple-mobile-web-app-capable" content="yes" />',
                'apple-status-bar' => '<meta name="apple-mobile-web-app-status-bar-style" content="black" />',
                'yandex_tabloid' => '<link rel="yandex-tableau-widget" href="/st/yandex/manifest.json">',
                'metrika' => '<script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter5843680 = new Ya.Metrika({ id:5843680, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="//mc.yandex.ru/watch/5843680" style="position:absolute; left:-9999px;" alt="" /></div></noscript>'
            ),
            'script' => array(
                'jquery',
                'modernizr',
                'scrollTo',
                'app'
            ),
            'css' => array(
                'desktop'
            ),
            'icon' => array(
                'favicon'
            ),
            'views' => array(
                'head' => array(
                    'authorized_mode' => true,
                    'allocated_paths' => array(
                        array(
                            'range' => array(4, 4),
                            'value' => 'common/head/head.auth.hasnt_sub'
                        ),
                        array(
                            'range' => array(5, 5),
                            'value' => 'common/head/head.auth'
                        ),
                        array(
                            'range' => 'default',
                            'value' => 'common/head/head'
                        )
                    )
                ),
                'head.profile' => array(
                    'authorized_mode' => true,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'common/head/profile/profile'
                        )
                    )
                ),
                'footer' => array(
                    'authorized_mode' => true,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'common/footer/footer'
                        )
                    )
                )
            )
        )
    ),
    'resource_options' => array(
        'path' => '/application/resources/global.php'
    ),
    'db_options' => require Q_PATH.'/application/config/db.config.php'
);