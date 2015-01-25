<?php

return array(
    'actions' => array(
        'index' => 'index',
        'templates.get' => 'get'
    ),
    'module_includes' => array(
        'merge' => array(
            'module_views' => array(
                'material' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'material/index'
                        )
                    )
                ),
                'author' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'author/index'
                        )
                    )
                ),
                'expand' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'expand/index'
                        )
                    )
                ),
                'empty_block' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'empty_block/index'
                        )
                    )
                ),
                'index' => array(
                    'authorized_mode' => false,
                    'allocated_paths' => array(
                        array(
                            'range' => 'default',
                            'value' => 'index/index'
                        )
                    )
                )
            )
        )
    )
);