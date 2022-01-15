<?php

namespace PhpModules\Cli;

use Fhaculty\Graph\Graph;
use Graphp\GraphViz\GraphViz;
use PhpModules\Cli\Internal\ModulesResolver;

class GraphCommand
{


    public function __construct(private ModulesResolver $modulesResolver)
    {
    }

    public static function create(): GraphCommand
    {
        return new GraphCommand(new ModulesResolver());
    }

    public function run(): void
    {
        $modules = $this->modulesResolver->get();

        $graph = new Graph();

        $namespaceVertexMap = [];
        foreach ($modules->modules as $module) {
            if (!isset($namespaceVertexMap[(string)$module->namespace])) {
                /** @phpstan-ignore-next-line */
                $vertex = $graph->createVertex((string)$module->namespace);

                $namespaceVertexMap[(string)$module->namespace] = $vertex;
            }
        }

        foreach ($modules->modules as $module) {
            if (isset($namespaceVertexMap[(string)$module->namespace])) {
                $vertex = $namespaceVertexMap[(string)$module->namespace];
                foreach ($module->dependencies as $dependency) {
                    if (isset($namespaceVertexMap[(string)$dependency->namespace])) {
                        $otherVertex = $namespaceVertexMap[(string)$dependency->namespace];
                        $vertex->createEdgeTo($otherVertex);
                    }
                }
            }
        }

        $graphviz = new GraphViz();
        $graphviz->display($graph);
    }
}
