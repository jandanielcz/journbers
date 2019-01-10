<?php


namespace Journbers;


class User
{
    const SESSION_KEY = 'q4pv7oz2nr2hv25c';

    protected $roles = ['guest'];
    protected $id = null;
    protected $fullName = null;

    protected $session = null;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $vars = $session->load(self::SESSION_KEY);
        if ($vars !== null) {
            $this->roles = $vars['roles'];
            $this->id = $vars['id'];
            $this->fullName = $vars['fullName'];
        }
    }

    public function saveToSession()
    {
        $this->session->store(self::SESSION_KEY, [
            'roles' => $this->roles,
            'id' => $this->id,
            'fullName' => $this->fullName,
        ]);
        $this->session->touch();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }


    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}