<?php

return [
    'routes' => [
        // order from most specific
        ['get', '#^/login#', 'Page::Login'],
        ['get', '#^/logout#', 'User::Logout'],
        ['get', '#^/add#', 'Page::Add'],
        ['post', '#^/login#', 'User::Login'],
        ['get', '#^/$#', 'Page::Index']
    ],
    'envPrefix' => 'JOURNBERS_'
];