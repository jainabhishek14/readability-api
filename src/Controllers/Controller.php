<?php

namespace App\Controllers;

class Controller
{
    protected $container;

    public function __contruct($container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }
}
