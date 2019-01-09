<?php


namespace Journbers;


use Tracy\Debugger;

class Session
{

    /**
     * Session constructor.
     *
     * @param $sessionLifetime Session max age in seconds.
     */
    public function __construct($sessionLifetime)
    {
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

    public function touch()
    {
        $n = new \DateTimeImmutable();
        $_SESSION['lastUpdate'] = $n->format(\DateTime::ISO8601);
    }

}