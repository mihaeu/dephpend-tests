<?php

namespace Mihaeu\DephpendTests\Tests;

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../src/ArchitectureTest.php';

class ArchitectureTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        \analyzeDependencies(__DIR__ . '/../examples/mvc');
    }

    /** @test */
    public function modelViewControllerPattern()
    {
        $controllers = defineDependency('controllers')
            ->from('*\Controllers');

        $models = defineDependency('models')
            ->from('@Mihaeu\.+\Models@');

        $views = defineDependency('views')
            ->from('Mihaeu\DephpendTests\Example\MVC\Views');

        $dataSources = defineDependency('data sources')
            ->from('*\Database')
            ->and()
            ->from('*\GitHub\Api');

        $controllers
            ->mayDependOn($views)
            ->and()
            ->mayDependOn($models)
            ->butNothingElse();

        $models
            ->mayNotDependOn($views)
            ->and()
            ->mayNotDependOn($controllers)
            ->but()
            ->mayDependOn($dataSources);

        $views
            ->mayNotDependOnAnything();

        validateArchitecture();
    }
}
