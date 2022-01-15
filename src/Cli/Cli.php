<?php

namespace PhpModules\Cli;

class Cli
{


    /**
     * @param string[] $arguments
     */
    public function __construct(private array $arguments)
    {
    }

    /**
     * @param string[] $arguments
     */
    public static function create(array $arguments): Cli
    {
        return new Cli($arguments);
    }

    public function run(): void
    {
        $command = isset($this->arguments[1]) ? $this->arguments[1] : null;
        switch ($command) {
            case 'test':
                TestCommand::create()->run();
                break;
            default:
                die('Unknown command');
        }
    }
}