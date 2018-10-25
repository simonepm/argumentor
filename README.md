# argumentor

simple and easy PHP library for passing arguments and options to a PHP command script from command line.

## Usage

    <?php

    include "./argumentor.php"

    $command = new Argumentor\Command();

    $command->RegisterArgument("argumentA");
    $command->RegisterOption("optionA", "o");

    $command->Exec(function(\Argumentor\Arguments $arguments, \Argumentor\Options $options) {
        var_dump($arguments->Get("argumentA"));
        var_dump($options->Get("optionA"));
    });
