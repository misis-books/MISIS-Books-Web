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
                'dev'
            ),
            'css' => array(
                'dev'
            ),
            'module_views' => array(
                'dev_wrapper' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/index'
                        )
                    )
                ),
                'description' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/description'
                        )
                    )
                ),
                'apiusage' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/apiusage'
                        )
                    )
                ),
                'api_requests' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/api_requests'
                        )
                    )
                ),
                'auth.register' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/auth.register'
                        )
                    )
                ),
                'methods_list' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/methods_list'
                        )
                    )
                ),
                'materials.search' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/materials.search'
                        )
                    )
                ),
                'materials.getpopular' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/materials.getpopular'
                        )
                    )
                ),
                'materials.getCategories' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/materials.getCategories'
                        )
                    )
                ),
                'add_edition' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'popups/add_edition'
                        )
                    )
                ),
                'copy_link' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'popups/copy_link'
                        )
                    )
                )
            )
        ),
        'replace' => array(
            'title' => 'MISIS Books API',
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