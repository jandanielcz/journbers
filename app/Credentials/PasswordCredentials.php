<?php


namespace Journbers\Credentials;


class PasswordCredentials
{
    protected $name = null;
    protected $password = null;

    public function __construct($name, $password)
    {
        $this->name = $name;
        $this->password = $password;
    }

    // TODO: use some CredentialStorage
    public function validate()
    {
        if ($this->password === '123' && $this->name = 'name') {
            return ['driver'];
        }

        throw new InvalidCredentialsException('Credentials not valid.');

    }
}