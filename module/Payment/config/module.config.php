<?php

return array(
    'actions' => array(
        'index' => 'index',
        'success' => 'success',
        'fail' => 'error',
        'add' => 'add',
        'add.payeer' => 'addPayeer',
        'promo' => 'promo',
        'activatePromo' => 'activatePromo'
    ),
    'module_includes' => array(
        'merge' => array(
            'meta' => array(
                'keywords' => '<meta name="keywords" content="...">',
                'description' => '<meta name="description" content="...">'
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
                            'range' => array(4, 5),
                            'value' => 'index/content/content.auth'
                        ),
                        array(
                            'range' => 'default',
                            'value' => 'index/content/content.not_auth'
                        )
                    )
                ),
                'success' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'success/main/main'
                        )
                    )
                ),
                'success.content' => array(
                    'authorized_mode' => true,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'success/content/content.auth'
                        )
                    )
                ),
                'error' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'error/main/main'
                        )
                    )
                ),
                'error.content' => array(
                    'authorized_mode' => true,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'error/content/content.auth'
                        )
                    )
                ),
                'main.promo' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'promo/main'
                        )
                    )
                ),
                'main.promo.confirm' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'promo/confirm'
                        )
                    )
                ),
                'promoActivate' => array(
                    'authorized_mode' => true,
                    'allocated_paths' => array(
                        array(
                            'range' => array(4, 5),
                            'value' => 'promo/activatePromo.auth'
                        ),
                        array(
                            'range' => 'default',
                            'value' => 'promo/activatePromo'
                        )
                    )
                )
            )
        ),
        'replace' => array(
            'title' => 'Оплата подписки | MISIS Books — Быстрый доступ к материалам электронной библиотеки НИТУ «МИСиС»',
            'common_views' => array(
                'head' => array(
                    'authorized_mode' => true,
                    'allocated_paths' => array(
                        array(
                            'range' => array(4, 5),
                            'value' => 'payment/head/head.auth'
                        ),
                        array(
                            'range' => 'default',
                            'value' => 'payment/head/head'
                        )
                    )
                )
            )
        )
    )
);