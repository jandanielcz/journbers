<?php

return [
    'routes' => [
        // order from most specific
        ['get', '#^/login#', 'Page::Login'],
        ['post', '#^/login#', 'User::Login'],
        ['get', '#^/#', 'Page::Index']
    ]
];