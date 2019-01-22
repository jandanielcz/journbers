<?php


namespace Journbers;


class Request
{
    protected $user = null;
    protected $segments = null;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return null|User
     */
    public function user()
    {
        return $this->user;
    }

    public function segments()
    {
        if ($this->segments === null) {
            $this->segments = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
        }

        return $this->segments;
    }

    public function segment($index)
    {
        if (isset($this->segments()[$index])) {
            return $this->segments()[$index];
        }
        return null;
    }
}
