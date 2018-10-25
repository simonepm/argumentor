<?php

namespace Argumentor;

class Arguments {

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

class Options {

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

class Command {

    protected $arguments    = [];
    protected $options      = [];
    protected $shortsmap    = [];

    public function RegisterArgument(string $name)
    {

        $name = ltrim($name, "-");

        $this->arguments[$name] = NULL;

        return TRUE;

    }

    public function RegisterOption(string $name, string $short = "")
    {

        $name = ltrim($name, "-");

        $short = ltrim($short, "-");

        $this->options[$name] = NULL;

        if (!empty($short)) $this->shortsmap[$short] = $name;

        return TRUE;

    }

    public function Exec(callable $callback, $funcargs = NULL)
    {

        global $argv;

        for ($i = 1; $i < count($argv); $i++) {

            $arg = $argv[$i];

            if (is_string($arg)) {

                $argtrim = ltrim($argv[$i], "-");

                if (strlen($argtrim) > 0) {

                    if ($arg[0] == "-") {

                        if ($arg[1] !== "-" && strlen($arg) > 2 && $arg[2] !== "=") {

                            $flags = str_split($argtrim);

                            foreach ($flags as $flag) {

                                $name = array_key_exists($flag, $this->shortsmap) ? $this->shortsmap[$flag] : FALSE;

                                if ($name && array_key_exists($name, $this->options)) {

                                    $this->options[$name] = TRUE;

                                }

                            }

                        } else {

                            $names = explode("=", $argtrim);

                            $name  = array_shift($names);

                            $name  = array_key_exists($name, $this->shortsmap) ? $this->shortsmap[$name] : $name;

                            if (array_key_exists($name, $this->options)) {

                                if (count($names) > 0) {

                                    $this->options[$name] = array_pop($names);

                                } else if (isset($argv[$i + 1]) && $argv[$i + 1][0] !== "-") {

                                    $this->options[$name] = $argv[$i + 1];

                                    $i++;

                                } else {

                                    $this->options[$name] = TRUE;

                                }

                            }

                        }

                    } else {

                        foreach ($this->arguments as $name => $argument) {

                            if ($this->arguments[$name] === NULL) {

                                $this->arguments[$name] = $arg;

                                break;

                            }

                        }

                    }

                }

            }

        }

        $funcargs = func_get_args();

        array_shift($funcargs);

        return $callback(new Arguments($this->arguments), new Options($this->options), $funcargs);

    }

}