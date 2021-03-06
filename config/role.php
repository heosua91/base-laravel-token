<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the roles that will be used to set permission
    | for users.
    | name_route is Route::get('abc')->name('abc')
    | Example:
    |   'roles' => [

            1 => [
                'name' => 'name_route',
                'child_roles' => [
                    1 => [
                        'name' => 'name_route',
                        'child_roles' => []
                    ],
                    2 => [
                        'name' => 'name_route',
                        'child_roles' => [
                            1 => [
                                'name' => 'name_route',
                                'child_roles' => []
                            ]
                        ]
                    ]
                ]
            ],

            2 => [
                'name' => 'name_route',
                'child_roles' => [
                    1 => [
                        'name' => 'name_route',
                        'child_roles' => []
                    ]
                ]
            ],

        ],
    |
    */
    
    'roles' => [

        1 => [
            'name' => 'user',
            'child_roles' => [
            ]
        ],

    ],
];