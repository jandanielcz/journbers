<?php


namespace Journbers;


class Template
{

    protected $templateName = null;

    public function __construct($templateName)
    {
        $this->templateName = $templateName;
    }

    public function display()
    {
        include sprintf('templates/%s.php', $this->templateName);
    }
}