# argumentor

simple and easy PHP library for passing arguments and options to a PHP command script from command line.

## Usage

    # php example.php testArgument -o testOption

    <?php
    
        // example.php

        use Simonepm\Argumentor\Command
        use Simonepm\Argumentor\Argument
        use Simonepm\Argumentor\Option

        $command = new Command();

        $command->RegisterArgument("argument");
        $command->RegisterOption("option", "o");

        $command->Exec(function(Argument $argument, Option $option) {

            echo $argument->Get("argument") . PHP_EOL; // echo "testArgument\n"

            echo $option->Get("option") . PHP_EOL; // echo "testOption\n"

        });
