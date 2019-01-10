<?php


namespace Journbers;


class Request
{
    protected $user = null;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function user()
    {
        return $this->user;
    }

}