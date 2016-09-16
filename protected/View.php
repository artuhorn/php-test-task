<?php

namespace App;

use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class View
 * @package App
 */
class View
{
    protected $data = [];

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    /**
     * @param string $template
     * @return string
     */
    public function render(string $template)
    {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/views/');
        $twig = new Twig_Environment($loader, [
            'cache' => __DIR__ . '/cache/',
            'auto_reload' => true
        ]);

        return $twig->render($template, $this->data);
    }

    /**
     * @param string $template
     */
    public function display(string $template)
    {
        echo $this->render($template);
    }
}
