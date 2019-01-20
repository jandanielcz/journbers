<?php

return [
    'routes' => [
        // order from most specific
        ['get', '#^/login#', 'Page::Login'],
        ['get', '#^/logout#', 'User::Logout'],
        ['get', '#^/add#', 'Page::Add'],
        ['post', '#^/add#', 'Entry::Add'],
        ['post', '#^/login#', 'User::Login'],
        ['get', '#^/$#', 'Page::Index'],
        ['get', '#^/([a-z]*)[/]{0,1}$#', 'Page::Trips']
    ],
    'envPrefix' => 'JOURNBERS_',
    'hardcodedCar' => 'golf'
];