<?php

namespace PhpModules\Cli;

use OndraM\CiDetector\CiDetector;
use PhpModules\Cli\ErrorFormatter\ErrorFormatter;
use PhpModules\Cli\ErrorFormatter\ErrorsConsoleStyle;
use PhpModules\Cli\ErrorFormatter\GithubErrorFormatter;
use PhpModules\Cli\ErrorFormatter\Output;
use PhpModules\Cli\ErrorFormatter\Symfony\SymfonyOutput;
use PhpModules\Cli\ErrorFormatter\Symfony\SymfonyStyle;
use PhpModules\Cli\ErrorFormatter\TableErrorFormatter;
use PhpModules\Cli\Internal\ModulesResolver;
use PhpModules\Lib\Analyzer;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class TestCommand
{


    public function __construct(private ModulesResolver $modulesResolver, private ErrorFormatter $errorFormatter)
    {
    }

    public static function create(): TestCommand
    {
        /** @var ErrorFormatter $errorFormatter */
        $errorFormatter = new TableErrorFormatter();
        $ciDetector = new CiDetector();
        if ($ciDetector->isCiDetected()) {
            $ci = $ciDetector->detect();
            if ($ci->getCiName() === CiDetector::CI_GITHUB_ACTIONS) {
                $errorFormatter = new GithubErrorFormatter(new TableErrorFormatter());
            }
        }
        return new TestCommand(new ModulesResolver(), $errorFormatter);
    }

    public function run(): void
    {
        $modules = $this->modulesResolver->get();
        $result = Analyzer::create($modules)->analyze();
        $this->errorFormatter->formatErrors($result, $this->output());
    }

    private function output(): Output
    {
        $output = new ConsoleOutput();
        $errorConsoleStyle = new ErrorsConsoleStyle(new StringInput(''), $output);
        return new SymfonyOutput($output, new SymfonyStyle($errorConsoleStyle));
    }
}