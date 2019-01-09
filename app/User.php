<?php


namespace Journbers;


class User
{
    protected $roles = ['guest'];

    public function __construct()
    {

    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }
}