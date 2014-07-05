<?php

return array(
    'actions' => array(
        'index' => 'index',
        'upd' => 'upd'
    ),
    'module_includes' => array(
        'merge' => array(
            'meta' => array(
                'robots' => '<meta name="robots" content="noindex"/>'
            ),
            'script' => array(
                'spinner',
                'jquery.cookie',
                'jquery.formstyler',
                'admin'
            ),
            'module_views' => array(
                'content' => array(
                    'authorized_mode' => false,
                    'path' => 'index/main'
                ),
                'add_edition' => array(
                    'authorized_mode' => false,
                    'path' => 'popups/add_edition'
                )
            )
        ),
        'replace' => array(
            'title' => 'Статистика'
        )
    )
);