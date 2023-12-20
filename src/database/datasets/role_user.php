<?php

return [
    'columns'   => [
        'user_id','role_id',
    ],
    'lookups' => [
        'user_id' => [App\Models\User::class, 'email'],
        'role_id' => [App\Models\Role::class, 'nama'],
    ],
    'imports'  => [
        ['admin@enigma.id', 'Admin'],
        ['staff@enigma.id', 'User'],
    ],
];