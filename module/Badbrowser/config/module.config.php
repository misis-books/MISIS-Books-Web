<?php

return array(
    'actions' => array(
        'index' => 'index'
    ),
    'module_includes' => array(
        'merge' => array(
            'meta' => array(
                'keywords' => '<meta name="keywords" content="Мисис, библиотека, скачать, пособия, электронная библиотека, онлайн">',
                'description' => '<meta name="description" content="Онлайн версия скачивателя материалов из электронной библиотеки НИТУ МИСиС. Никаких авторизаций. Быстрый доступ к материалам в любое время суток. Вы можете скачать в любой момент материалы библиотеки прямо с Ваших мобильных устройств и пользоваться ими на своих парах.">'
            ),
            'module_views' => array(
                'badbrowser' => array(
                    'authorized_mode' => false,
                    'path' => 'index/badbrowser'
                ),
                'add_edition' => array(
                    'authorized_mode' => false,
                    'path' => 'popups/add_edition'
                )
            )
        ),
        'replace' => array(
            'title' => 'Браузер устарел',
            'meta' => array(
                'charset' => '<meta http-equiv="content-type" content="text/html; charset=utf-8">',
                'ie' => '<meta http-equiv="X-UA-Compatible" content="IE=edge">',
                'yandex_tabloid' => '<link rel="yandex-tableau-widget" href="/st/yandex/manifest.json">'
            )
        )
    )
);