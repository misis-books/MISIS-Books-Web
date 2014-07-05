<?php

return array(
    'actions' => array(
        'index' => 'index',
        'apiusage' => 'apiusage',
        'api_requests' => 'apiRequests',
        'auth.register' => 'authRegister',
        'methods_list' => 'methodsList',
        'materials.search' => 'search',
        'materials.getpopular' => 'getPopular',
        'materials.getCategories' => 'getCategories',
        'dev.content' => 'getContent'
    ),
    'module_includes' => array(
        'merge' => array(
            'meta' => array(
                'keywords' => '<meta name="keywords" content="Мисис, библиотека, скачать, пособия, электронная библиотека, онлайн, документация, API">',
                'description' => '<meta name="description" content="Документация по API">'
            ),
            'script' => array(
                'spinner',
                'jquery.cookie',
                'dev'
            ),
            'css' => array(
                'dev'
            ),
            'module_views' => array(
                'dev_wrapper' => array(
                    'authorized_mode' => false,
                    'path' => 'index/index'
                ),
                'description' => array(
                    'authorized_mode' => false,
                    'path' => 'index/description'
                ),
                'apiusage' => array(
                    'authorized_mode' => false,
                    'path' => 'index/apiusage'
                ),
                'api_requests' => array(
                    'authorized_mode' => false,
                    'path' => 'index/api_requests'
                ),
                'auth.register' => array(
                    'authorized_mode' => false,
                    'path' => 'index/auth.register'
                ),
                'methods_list' => array(
                    'authorized_mode' => false,
                    'path' => 'index/methods_list'
                ),
                'materials.search' => array(
                    'authorized_mode' => false,
                    'path' => 'index/materials.search'
                ),
                'materials.getpopular' => array(
                    'authorized_mode' => false,
                    'path' => 'index/materials.getpopular'
                ),
                'materials.getCategories' => array(
                    'authorized_mode' => false,
                    'path' => 'index/materials.getCategories'
                ),
                'add_edition' => array(
                    'authorized_mode' => false,
                    'path' => 'popups/add_edition'
                ),
                'copy_link' => array(
                    'authorized_mode' => false,
                    'path' => 'popups/copy_link'
                )
            )
        ),
        'replace' => array(
            'title' => 'MISIS Books API'
        )
    )
);