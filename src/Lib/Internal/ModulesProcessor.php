<?php

namespace PhpModules\Lib\Internal;

use PhpModules\Exceptions\PHPModulesException;
use PhpModules\Lib\Modules;
use PhpModules\Lib\SubModules;
use SplFileInfo;

class ModulesProcessor
{

    public function __construct(private Modules $modules)
    {
    }

    /**
     * @throws PHPModulesException
     */
    public function process(): Modules
    {
        if ($this->modules->shouldScanDirectories) {
            $subModules = $this->findSubModules($this->modules->path);
            foreach ($subModules as $subModule) {
                $this->modules->register($subModule->modules);
            }
        }
        return $this->modules;
    }

    /**
     * @param string $rootPath
     * @return SubModules[]
     */
    private function findSubModules(string $rootPath): array
    {
        //Recursively find all modules.php files and require them
        $modules = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($rootPath));
        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->getPath() !== $rootPath && $file->getFilename() === 'modules.php') {
                $subModules = require $file->getPathname();
                if (!$subModules instanceof SubModules) {
                    throw new PHPModulesException($file->getPathname() . ' must return an instance of SubModules');
                }
                $modules[] = $subModules;
            }
        }

        return $modules;
    }
}