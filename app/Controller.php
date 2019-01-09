<?php


namespace Journbers;


class Controller
{
    protected function redirect($path)
    {

        header(sprintf('Location: %s', $path), true, 301);
    }

    protected function exit()
    {
        exit;
    }
}