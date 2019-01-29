<?php


namespace Journbers;


class Session
{

    /**
     * Session constructor.
     *
     * @param $sessionLifetime Session max age in seconds.
     */
    public function __construct($sessionLifetime)
    {
        ini_set('session.cookie_lifetime', $sessionLifetime);
        ini_set('session.gc_maxlifetime', $sessionLifetime);
        session_start();
        $this->invalidateOld($sessionLifetime);
    }

    protected function invalidateOld($sessionLifetime)
    {
        // TODO: timezone
        $sessionLastUpdate = (isset($_SESSION['lastUpdate'])) ? new \DateTimeImmutable($_SESSION['lastUpdate']) : INF;
        if ($sessionLastUpdate === INF) {
            return;
        }

        $n = new \DateTimeImmutable();
        $sessionAge = abs($sessionLastUpdate->getTimestamp() - $n->getTimestamp());

        if ($sessionAge > $sessionLifetime) {
            session_unset();
        }
    }

    public function store($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function load($key)
    {
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : null;
    }

    public function touch()
    {
        $n = new \DateTimeImmutable();
        $_SESSION['lastUpdate'] = $n->format(\DateTime::ISO8601);
    }

    public function destroy($key)
    {
        unset($_SESSION[$key]);
    }
}
