<?php

return array(
    'actions' => array(
        'index' => 'index'
    ),
    'module_includes' => array(
        'merge' => array(
            'meta' => array(
                'keywords' => '<meta name="keywords" content="MISIS Books. Статистика.">'
            ),
            'script' => array(),
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
                            'range' => 'default',
                            'value' => 'index/content/content'
                        )
                    )
                )
            )
        ),
        'replace' => array(
            'title' => 'Статистика | MISIS Books — Быстрый доступ к материалам электронной библиотеки НИТУ «МИСиС»',
            'common_views' => array(
                'head' => array(
                    'authorized_mode' => true,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'stats/head/head.auth'
                        )
                    )
                )
            )
        )
    )
);