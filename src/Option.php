<?php

namespace Simonepm\Argumentor;

class Option {

    private $options = [];

    public function __construct(array $options)
    {

        $this->options = $options;

        return TRUE;

    }

    public function Get(string $name)
    {

        $name = ltrim($name, "-");

        return isset($this->options[$name]) ? $this->options[$name] : NULL;

    }

}