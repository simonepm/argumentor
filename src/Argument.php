<?php

namespace Simonepm\Argumentor;

class Argument {

    private $arguments = [];

    public function __construct(array $arguments)
    {

        $this->arguments = $arguments;

        return TRUE;

    }

    public function Get(string $name)
    {

        $name = ltrim($name, "-");

        return isset($this->arguments[$name]) ? $this->arguments[$name] : NULL;

    }

}