<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Structures des rôles et permissions par solution (Application SaaS)
    |--------------------------------------------------------------------------
    */
    'solutions' => [

        // Code solution : school
        'school' => [
            'roles' => [
                'admin' => [
                    'eleves' => 'c,r,u,d',
                    'enseignants' => 'c,r,u,d',
                    'notes' => 'c,r,u,d',
                ],
                'directeur' => [
                    'eleves' => 'r,u',
                    'enseignants' => 'r,u',
                ],
                'enseignant' => [
                    'notes' => 'c,r,u',
                ],
                'secretaire' => [
                    'eleves' => 'c,r,u',
                ],
                'comptable' => [
                    'factures' => 'c,r,u,d',
                ],
            ],
        ],

        // Code solution : hotel
        'hotel' => [
            'roles' => [
                'fondateur' => [
                    'chambres' => 'c,r,u,d',
                    'reservations' => 'c,r,u,d',
                ],
                'receptionniste' => [
                    'reservations' => 'c,r,u',
                ],
            ],
        ],

    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],

    'create_users' => true,
    'truncate_tables' => true,

];