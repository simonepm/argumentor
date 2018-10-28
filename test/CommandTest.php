<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Simonpm\Argumentor\Command;

class CommandTest extends PHPUnit\Framework\TestCase
{

    private $command;

    protected static function getPrivateMethod($name) {

        $class = new ReflectionClass('Command');

        $method = $class->getMethod($name);

        $method->setAccessible(true);

        return $method;

    }
 
    protected function setUp()
    {
        
        $this->command = new Command();

        $this->command->RegisterArgument("argument");
        $this->command->RegisterOption("option");
        $this->command->RegisterArgument("middle");
        $this->command->RegisterOption("short", "s");
        $this->command->RegisterOption("a", "a");
        $this->command->RegisterOption("b", "b");
        $this->command->RegisterOption("c", "c");

    }

    protected function tearDown()
    {

        $this->command = NULL;
        
    }
 
    public function testRegisterArgument()
    {

        $this->assertTrue($this->command->RegisterArgument("argumentA"));

    }
 
    public function testRegisterOption()
    {

        $this->assertTrue($this->command->RegisterOption("optionA"));
        $this->assertTrue($this->command->RegisterOption("shortOptionD", "d"));
        $this->assertFalse($this->command->RegisterOption("shortOptionD", "d"));
        $this->assertFalse($this->command->RegisterOption("shortOptionE", "EE"));
        $this->assertFalse($this->command->RegisterOption("shortOptionF", "F"));

    }

    public function testArgv()
    {

        $output = self::getPrivateMethod('argv')->invokeArgs($this->command, [
            "lorem ipsum",
            "--option",
            "dolor sit amet",
            "nowhere",
            "-s",
            "I'm short",
            "-abc"
        ]);

        $this->assertEquals("lorem ipsum", $output[0]->Get("argument"));
        $this->assertEquals("dolor sit amet", $output[1]->Get("option"));
        $this->assertEquals("nowhere", $output[0]->Get("middle"));
        $this->assertEquals("I'm short", $output[1]->Get("short"));
        $this->assertTrue($output[1]->Get("a"));
        $this->assertTrue($output[1]->Get("b"));
        $this->assertTrue($output[1]->Get("c"));

    }

}
