<?php

return [
    'routes' => [
        // order from most specific
        ['get', '#^/login#', 'Page::Login'],
        ['get', '#^/logout#', 'User::Logout'],
        ['get', '#^/([a-z]*)/add[/]{0,1}#', 'Page::Add'],
        ['post', '#^/add#', 'Entry::Add'],
        ['post', '#^/login#', 'User::Login'],
        ['post', '#^/fill-space#', 'Page::FillSpace'],
        ['get', '#^/$#', 'Page::Index'],
        ['get', '#^/edit/([0-9]*)[/]{0,1}$#', 'Page::Edit'],
        ['get', '#^/remove/([0-9]*)[/]{0,1}$#', 'Entry::Remove'],
        ['post', '#^/edit#', 'Entry::Edit'],
        ['get', '#^/([a-z]*)[/]{0,1}$#', 'Page::Trips']
    ],
    'envPrefix' => 'JOURNBERS_',
    'hardcodedCar' => 'golf',
    'appName' => 'B.L.B.'
];