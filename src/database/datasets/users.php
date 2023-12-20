<?php

return [
    'columns'   => [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
    ],
   
    'imports'  => [
        [
            'Alice',
            'alice@mail.com',
            now(),
            '123456',
            Str::random(10)
        ],
        [
            'Bob',
            'bob@mail.com',
            now(),
            '123456',
            Str::random(10)
        ],
    ],
];