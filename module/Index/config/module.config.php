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
            'script' => array(
                'main',
                'spinner',
                'jquery.cookie',
                'jquery.formstyler',
                'predictor',
                'belovTrie'
            ),
            'css' => array(
                'style',
                'formstyler'
            ),
            'module_views' => array(
                'content' => array(
                    'authorized_mode' => false,
                    'path' => 'index/main'
                ),
                'badbrowser' => array(
                    'authorized_mode' => false,
                    'path' => 'index/badbrowser'
                ),
                'add_author' => array(
                    'authorized_mode' => false,
                    'path' => 'popups/add_author'
                ),
                'add_edition' => array(
                    'authorized_mode' => false,
                    'path' => 'popups/add_edition'
                ),
                'copy_link' => array(
                    'authorized_mode' => false,
                    'path' => 'popups/copy_link'
                ),
                'preview' => array(
                    'authorized_mode' => false,
                    'path' => 'popups/preview'
                )
            )
        ),
        'replace' => array(
            'title' => 'Быстрый доступ к материалам электронной библиотеки НИТУ МИСиС'
        )
    )
);