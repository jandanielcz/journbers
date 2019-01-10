<?php


namespace Journbers\Credentials;


use Journbers\Data\CredentialsStore;

class PasswordCredentials
{
    protected $name = null;
    protected $password = null;

    public function __construct($name, $password)
    {
        $this->name = $name;
        $this->password = $password;
    }

    public function validate(CredentialsStore $cs)
    {
        $userInfo = $cs->validateCredentials($this->name, $this->password);

        return $userInfo;
    }
}