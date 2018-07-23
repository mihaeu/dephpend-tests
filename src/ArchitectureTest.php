<?php

use Mihaeu\PhpDependencies\Dependencies\Dependency;
use Mihaeu\PhpDependencies\Dependencies\DependencyMap;
use Mihaeu\PhpDependencies\Util\DependencyContainer;
use PHPUnit\Framework\Assert;

function analyzeDependencies(string ...$paths)
{
    $dependencyContainer = new DependencyContainer([]);
    $analyzer = $dependencyContainer->staticAnalyser();
    foreach ($paths as $path) {
        $files = $dependencyContainer->phpFileFinder();
        $GLOBALS['dePHPend/dependencies'][$path] = $analyzer->analyse($files->find(new \SplFileInfo($path)));
    }
}

function defineDependency(string $name): Definition
{
    $dependency = new Definition($name);
    $GLOBALS['dePHPend/rules'][$name] = $dependency;
    return $dependency;
}

function validateArchitecture()
{
    $violations = [];
    foreach ($GLOBALS['dePHPend/dependencies'] as $dependencyMap) {
        /** @var DependencyMap $dependencyMap */
        $dependencyMap->each(function (Dependency $from, Dependency $to) use (&$violations) {
            foreach ($GLOBALS['dePHPend/rules'] as $name => $rule) {
                /** @var Definition $rule */
                if ($rule->matches($from)) {
                    foreach ($rule->blacklist() as $blacklistItem) {
                        /** @var Definition $blacklistItem */
                        if ($blacklistItem->matches($to)) {
                            $violations['blacklist'][$name] = "$from from $name may not depend on $to from {$blacklistItem->name()}.";
                        }
                    }

                    foreach ($rule->whitelist() as $whitelistItem) {
                        /** @var Definition $whitelistItem */
                        if (!$whitelistItem->matches($to)) {
                            $violations['whitelist'][$name] = "$from from $name may not depend on $to from {$whitelistItem->name()}.";
                        }
                    }
                }
            }
        });
    }
    Assert::assertEmpty($violations);
}

class Definition
{
    /** @var string */
    private $name;

    /** @var array */
    private $namespaces = [];

    /** @var array */
    private $regexes = [];

    /** @var array */
    private $whitelist = [];

    /** @var array */
    private $blacklist = [];

    /** @var bool */
    private $isWhitelistTest = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function from(string $description): self
    {
        if (preg_match('/^@.+@$/', $description) === true) {
            $this->regexes[] = $description;
        } elseif (strpos($description, '*') !== false) {
            $this->regexes[] = '@' . str_replace('*', '.*', $description) . '@';
        } else {
            $this->namespaces[] = $description;
        }
        return $this;
    }

    public function matches(Dependency $dependency): bool
    {
        foreach ($this->namespaces as $namespace) {
            if (strpos($namespace, $dependency->toString()) !== false) {
                return true;
            }
        }
        foreach ($this->regexes as $regex) {
            if (preg_match($regex, $dependency->toString())) {
                return true;
            }
        }
        return false;
    }

    public function fromRegex(string $regex): self
    {
        $this->regexes[] = $regex;
        return $this;
    }

    public function fromNamespaces(string $namespace): self
    {
        $this->namespaces[] = $namespace;
        return $this;
    }

    public function mayDependOn(Definition $dependency): self
    {
        $this->whitelist[$dependency->name()] = $dependency;
        return $this;
    }

    public function mayNotDependOn(Definition $dependency): self
    {
        $this->blacklist[$dependency->name()] = $dependency;
        return $this;
    }

    public function butNothingElse()
    {
        $this->isWhitelistTest = true;
    }

    public function isWhitelistTest(): bool
    {
        return $this->isWhitelistTest;
    }

    public function mayNotDependOnAnything()
    {
        $this->blacklist[] = Definition::anything();
    }

    public function but(): self
    {
        return $this;
    }

    public function and(): self
    {
        return $this;
    }

    public function or(): self
    {
        return $this;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function whitelist(): array
    {
        return $this->whitelist;
    }

    public function blacklist(): array
    {
        return $this->blacklist;
    }

    private static function anything(): Definition
    {
        return new Definition('*');
    }
}

//function defineLayer()
//{
//    return
//}
//
//function defineModule()
//{
//
//}
//
//function defineComponent()
//{
//
//}
//
//function defineContext()
//{
//
//}

//beforeClass() {
//    // setup
//$fileSet = $finder->find(__DIR__ . '/src')
//    ->addAll($finder->find(__DIR__ . '/vendor'));
//$dependencies = $staticAnalyser->analyse($fileSet);
//
//// setup shorthand
//analyse('path1', 'path2');
//}
//
//// test
//test() {
//$Models = defineLayer()
//    ->from('Some\Namespace\Bla')
//    ->from('/special.*regex/');
//
//$Views = defineComponent()
//    ->from('')
//    ->from('');
//
//$Controllers
//    ->dependsOn($Models)
//    ->but()
//    ->mayNotDependOn($Views);
//
//$util
//    ->mayNotHaveDependencies();
//
//$Views
//    ->mayNotBeDependentOn();
//
//package('Vendor/Namespace')
//    ->mayNotDependOn()
//    ->classesFromDir(__DIR__ . '/../vendor');
//
//classesMatching('/.*Service.*/')
//    ->mayOnlyDependOn()
//    ->classesMatching('/.*Provider.*/');
//}
