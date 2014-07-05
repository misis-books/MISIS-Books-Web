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
        'badbrowser' => 'Badbrowser'
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
                'noscript' => '<noscript><meta http-equiv="refresh" content="0; URL=/badbrowser"></noscript>'
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