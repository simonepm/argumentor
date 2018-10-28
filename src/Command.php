<?php

namespace Simonepm\Argumentor;

use Argument;
use Option;

class Command {

    protected $arguments    = [];
    protected $options      = [];
    protected $shortsmap    = [];

    private static function is_alpha($s) {

        return in_array($s, str_split("abcdefghijklmnopqrstuwxyz"));

    }

    public function RegisterArgument(string $name)
    {

        $name = ltrim($name, "-");

        $this->arguments[$name] = NULL;

        return TRUE;

    }

    public function RegisterOption(string $name, string $short = NULL)
    {

        $name = ltrim($name, "-");

        $short = ltrim($short, "-");

        if (!empty($short)) {

            if (!self::is_alpha($short)) return FALSE;

            if (!empty($this->shortsmap[$short])) return FALSE;

            $this->shortsmap[$short] = $name;

        }

        $this->options[$name] = NULL;

        return TRUE;

    }

    public function Exec(callable $callback)
    {
        
        $cargs = $this->argv();
        
        $fargs = array_slice(func_get_args(), 1);

        return $callback(...array_merge($cargs, $fargs));

    }

    private function argv()
    {

        global $argv;

        $argvCopy = $argv;

        $fargs = func_get_args();

        array_shift($argvCopy);

        if (!empty($fargs)) {

            $fargs = array_filter($fargs);

            array_walk($fargs, function($a) { if (!is_string($a)) throw new \Exception("invalid array of string"); });

            $argvCopy = $fargs;

        }

        $argvCopy = array_values($argvCopy);

        for ($i = 0; $i < count($argvCopy); $i++) {

            $arg = $argvCopy[$i];

            if (is_string($arg)) {

                $argTrim = ltrim($arg, "-");

                if (strlen($argTrim) > 0) {

                    if ($arg[0] == "-" && ($arg[1] == "-" || self::is_alpha($arg[1]))) {

                        if ($arg[1] !== "-" && isset($arg[2]) && $arg[2] !== "=") {

                            $flags = str_split($argTrim);

                            foreach ($flags as $flag) {

                                $name = array_key_exists($flag, $this->shortsmap) ? $this->shortsmap[$flag] : FALSE;

                                if ($name && array_key_exists($name, $this->options)) {

                                    $this->options[$name] = TRUE;

                                }

                            }

                        } else {

                            $names = explode("=", $argTrim);

                            $name  = array_shift($names);

                            $name  = array_key_exists($name, $this->shortsmap) ? $this->shortsmap[$name] : $name;

                            if (array_key_exists($name, $this->options)) {

                                if (count($names) > 0) {

                                    $this->options[$name] = array_pop($names);

                                } else if (isset($argvCopy[$i + 1]) && $argvCopy[$i + 1][0] !== "-") {

                                    $argNext = $argvCopy[$i + 1];

                                    $this->options[$name] = $argNext;

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

        return [ new Argument($this->arguments), new Option($this->options) ];

    }

}
