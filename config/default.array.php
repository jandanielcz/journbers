<?php

return [
    'routes' => [
        // order from most specific
        ['get', '#^/login#', 'Page::Login'],
        ['get', '#^/logout#', 'User::Logout'],
        ['get', '#^/([a-z]*)/add[/]{0,1}#', 'Page::Add'],
        ['post', '#^/add#', 'Entry::Add'],
        ['post', '#^/login#', 'User::Login'],
        ['get', '#^/$#', 'Page::Index'],
        ['get', '#^/edit/([0-9]*)[/]{0,1}$#', 'Page::Edit'],
        ['post', '#^/edit#', 'Entry::Edit'],
        ['get', '#^/([a-z]*)[/]{0,1}$#', 'Page::Trips']
    ],
    'envPrefix' => 'JOURNBERS_',
    'hardcodedCar' => 'golf'
];