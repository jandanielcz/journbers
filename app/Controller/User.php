<?php


namespace Journbers\Controller;


use Journbers\Controller;
use Journbers\Credentials\InvalidCredentialsException;
use Journbers\Template;

class User extends Controller
{
    public function login()
    {
        if (!isset($_POST['CredentialsType'])) {
            throw new \RuntimeException('Credentials type not defined in template.');
        }

        $wantedClass = sprintf('\\Journbers\\Credentials\\%sCredentials', ucfirst($_POST['CredentialsType']));
        if (!class_exists($wantedClass)) {
            throw new \RuntimeException(sprintf('Credentials type %s not implemented.', $wantedClass));
        }

        // TODO: too hardcoded
        $providedCredentials = new $wantedClass($_POST['User'], $_POST['Pass']);
        try {
            $rolesAccesible = $providedCredentials->validate();
        } catch (InvalidCredentialsException $e) {
            // TODO: Add flash message and redirect
        }

        // TODO: add roles to user (user should be part of Request accessible to all Controllers
        // TODO: save to session and touch session
        // TODO: redirect to index

    }
}