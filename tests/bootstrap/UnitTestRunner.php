<?php

namespace Lucinda\UnitTest;

use Lucinda\UnitTest\Runner\ClassInfo;
use Lucinda\UnitTest\Runner\ClassesFinder;
use Lucinda\UnitTest\Runner\UnitTest;
use Lucinda\UnitTest\Validator\SQL;

/**
 * Local compatibility runner for source methods whose PHP signatures cannot
 * match the unit test framework's Result-returning method contract.
 */
class Runner
{
    /**
     * @var UnitTest[]
     */
    private array $results = [];
    private ?string $testClassNameNamespaced;
    private bool $hasMatch = false;

    /**
     * @var array<string,string>
     */
    private const METHOD_ALIASES = [
        "__toString" => "toString",
    ];

    /**
     * @param Configuration $configuration
     * @param ?string       $testClassNameNamespaced Optional test class filter (fully qualified class name).
     * @throws Exception
     */
    public function __construct(Configuration $configuration, ?string $testClassNameNamespaced = null)
    {
        $this->testClassNameNamespaced = $testClassNameNamespaced;
        if ($configuration->getSQLDataSource()) {
            SQL::setDataSource($configuration->getSQLDataSource());
        }
        $apis = $configuration->getAPIs();
        foreach ($apis as $api) {
            $sourcesFinder = new ClassesFinder($api->getSourcesPath());
            $testsFinder = new ClassesFinder($api->getTestsPath());
            $this->setResults($sourcesFinder->getResults(), $testsFinder->getResults());
        }
        if ($this->testClassNameNamespaced && !$this->hasMatch) {
            $this->results[] = $this->getFailedUnitTest(
                $this->testClassNameNamespaced,
                "Test class not found in configured sources/tests"
            );
        }
    }

    /**
     * @param array<string,ClassInfo> $sourceFiles
     * @param array<string,ClassInfo> $testFiles
     */
    private function setResults(array $sourceFiles, array $testFiles): void
    {
        foreach ($sourceFiles as $infoSrc) {
            if ($infoSrc->isAbstract || $infoSrc->isInterface || $infoSrc->isEnum) {
                continue;
            }
            $srcClassName = $infoSrc->className;
            $testClassName = $srcClassName."Test";
            $srcNamespace = $infoSrc->namespace;
            $testNamespace = ($srcNamespace ? "Test\\".$srcNamespace : "");
            $testClassNameNamespaced = ($testNamespace ? $testNamespace."\\" : "").$testClassName;
            if ($this->testClassNameNamespaced && $this->testClassNameNamespaced!=$testClassNameNamespaced) {
                continue;
            }
            $this->hasMatch = true;
            if (!isset($testFiles[$testClassNameNamespaced])) {
                $this->results[] = $this->getFailedUnitTest(
                    $testClassNameNamespaced,
                    "Class not covered by unit tests"
                );
            } elseif ($testNamespace!=$testFiles[$testClassNameNamespaced]->namespace) {
                $this->results[] = $this->getFailedUnitTest(
                    $testClassNameNamespaced,
                    "Unit test namespace doesn't match namespace of class"
                );
            } else {
                $this->runAllClassMethods($testClassNameNamespaced, $infoSrc->methods, $testFiles);
            }
        }
    }

    /**
     * @param string                  $testClassNameNamespaced
     * @param array<string,string>    $methods
     * @param array<string,ClassInfo> $testFiles
     */
    private function runAllClassMethods(
        string $testClassNameNamespaced,
        array $methods,
        array $testFiles
    ): void {
        $testObject = new $testClassNameNamespaced();
        foreach ($methods as $method) {
            $testMethod = $this->getTestMethodName($method);
            if (!isset($testFiles[$testClassNameNamespaced]->methods[$testMethod])) {
                $this->results[] = $this->getFailedUnitTest(
                    $testClassNameNamespaced,
                    "Source method not covered by unit tests!",
                    $method
                );
            } else {
                $result = $testObject->{$testMethod}();
                $this->parseResults($result, $testClassNameNamespaced, $testMethod);
            }
        }
        foreach ($testFiles[$testClassNameNamespaced]->methods as $method) {
            if (str_starts_with($method, "__")) {
                continue;
            }
            if (!$this->isSourceMethodCoveredByTestMethod($method, $methods)) {
                $result = $testObject->{$method}();
                $this->parseResults($result, $testClassNameNamespaced, $method);
            }
        }
    }

    private function getTestMethodName(string $sourceMethod): string
    {
        return self::METHOD_ALIASES[$sourceMethod] ?? $sourceMethod;
    }

    /**
     * @param array<string,string> $sourceMethods
     */
    private function isSourceMethodCoveredByTestMethod(string $testMethod, array $sourceMethods): bool
    {
        foreach ($sourceMethods as $sourceMethod) {
            if ($this->getTestMethodName($sourceMethod) == $testMethod) {
                return true;
            }
        }
        return false;
    }

    private function parseResults(mixed $result, string $testClassNameNamespaced, string $method): void
    {
        if (is_array($result) && !empty($result)) {
            foreach ($result as $object) {
                if ($object instanceof Result) {
                    $unitTest = new UnitTest();
                    $unitTest->className = $testClassNameNamespaced;
                    $unitTest->methodName = $method;
                    $unitTest->result = $object;
                    $this->results[] = $unitTest;
                } else {
                    $this->results[] = $this->getFailedUnitTest(
                        $testClassNameNamespaced,
                        "Invalid unit test response",
                        $method
                    );
                }
            }
        } elseif ($result instanceof Result) {
            $unitTest = new UnitTest();
            $unitTest->className = $testClassNameNamespaced;
            $unitTest->methodName = $method;
            $unitTest->result = $result;
            $this->results[] = $unitTest;
        } else {
            $this->results[] = $this->getFailedUnitTest(
                $testClassNameNamespaced,
                "Invalid unit test response",
                $method
            );
        }
    }

    private function getFailedUnitTest(string $testClassNameNamespaced, string $message, string $method = ""): UnitTest
    {
        $unitTest = new UnitTest();
        $unitTest->className = $testClassNameNamespaced;
        if ($method) {
            $unitTest->methodName = $method;
        }
        $unitTest->result = new Result(false, $message);
        return $unitTest;
    }

    /**
     * @return UnitTest[]
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
