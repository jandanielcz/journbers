<?php

/**
 * This file can be overwritten by `custom.array.php`
 */

return [
    'routes' => [
        // order from most specific
        // format [Method, Regex, Controller::Method]

        ['get', '#^/login#', 'Page::Login'],
        ['get', '#^/logout#', 'User::Logout'],
        ['get', '#^/([a-z]*)/add[/]{0,1}#', 'Page::Add'],
        ['post', '#^/add#', 'Entry::Add'],
        ['post', '#^/login#', 'User::Login'],

        ['post', '#^/fill-space#', 'Page::FillSpace'],
        ['post', '#^/space-to-start#', 'Entry::SpaceToStart'],
        ['post', '#^/space-to-end#', 'Entry::SpaceToEnd'],
        ['get', '#^/$#', 'Page::Index'],
        ['get', '#^/edit/([0-9]*)[/]{0,1}$#', 'Page::Edit'],

        ['get', '#^/remove/([0-9]*)[/]{0,1}$#', 'Entry::Remove'],
        ['post', '#^/edit#', 'Entry::Edit'],
        ['get', '#^/([a-z]*)[/]{0,1}$#', 'Page::Trips']
    ],
    'access' => [
        'Page::Login' => [\Journbers\Controller::ANY_ROLE],
        'User::Logout' => [\Journbers\Controller::ANY_ROLE],
        'Page::Add' => ['driver'],
        'Entry::Add' => ['driver'],
        'User::Login' => [\Journbers\Controller::ANY_ROLE],

        'Page::FillSpace' => ['driver'],
        'Entry::SpaceToStart' => ['driver'],
        'Entry::SpaceToEnd' => ['driver'],
        'Page::Index' => ['driver'],
        'Page::Edit' => ['driver'],

        'Entry::Remove' => ['driver'],
        'Entry::Edit' => ['driver'],
        'Page::Trips' => ['driver']
    ],
    // Prefix used for Env vars, in application VARS are used without prefix.
    'envPrefix' => 'JOURNBERS_',
    // Used for now, because app should support only one car for now.
    'hardcodedCar' => 'golf',
    // Shown in title and navigation.
    'appName' => 'Journbers',
    // Log configuration
    'maxLogSize' => 1024 * 1024 * 10
];
