# argumentor

simple and easy PHP library for passing arguments and options to a PHP command script from command line.

## Installation

    composer require simonepm/argumentor

## Usage

    # php example.php testArgument -o testOption

    <?php // example.php
        
        require_once "vendor/autoload.php";

        use Simonepm\Argumentor\Command;
        use Simonepm\Argumentor\Argument;
        use Simonepm\Argumentor\Option;

        $command = new Command();

        $command->RegisterArgument("argument");
        $command->RegisterOption("option", "o");

        $command->Exec(function(Argument $argument, Option $option) {

            echo $argument->Get("argument") . PHP_EOL; // "testArgument\n"

            echo $option->Get("option") . PHP_EOL; // "testOption\n"

        });
