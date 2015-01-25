<?php

return array(
    'actions' => array(
        'index' => 'index'
    ),
    'module_includes' => array(
        'merge' => array(
            'meta' => array(
                'keywords' => '<meta name="keywords" content="онлайн,библиотека,мисис,misis,books,misis books,скачать пособие мисис,онлайн скачиватель,скачать методичку ниту мисис,методички мисис">',
                'description' => '<meta name="description" content="Комфортный доступ ко всем материалам электронной библиотеки НИТУ МИСиС с любых устройств. Синхронизация Избранного между всеми Вашими устройствами.">'
            ),
            'script' => array(
                'payment'
            ),
            'css' => array(),
            'module_views' => array(
                'main' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/main/main'
                        )
                    )
                ),
                'content' => array(
                    'authorized_mode' => true,
                    'allocated_paths' => array(
                        array(
                            'range' => array(4, 4),
                            'value' => 'index/content/content.auth.hasnt_sub'
                        ),
                        array(
                            'range' => array(5, 5),
                            'value' => 'index/content/content.auth.has_sub'
                        ),
                        array(
                            'range' => 'default',
                            'value' => 'index/content/content.not_auth'
                        )
                    )
                )
            )
        ),
        'replace' => array(
            'title' => 'MISIS Books — Быстрый доступ к материалам электронной библиотеки НИТУ «МИСиС»'
        )
    )
);