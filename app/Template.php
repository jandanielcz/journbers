<?php


namespace Journbers;


class Template
{

    protected $templateName = null;
    protected $vars = [];

    public function __construct($templateName)
    {
        $this->templateName = $templateName;
    }

    public function vars($vars)
    {
        $this->vars = array_merge($this->vars, $vars);
    }

    public function display($vars)
    {
        $this->vars($vars);

        $vars = $this->vars;

        include sprintf('templates/%s.php', $this->templateName);
    }
}
