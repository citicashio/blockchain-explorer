<?php declare(strict_types = 1);

/**
 * @testCase
 */

namespace Tests;

use Tester\Assert;

$container = include __DIR__ . '/bootstrap.php';

class ExampleTest extends BaseTestCase
{

	public function testSomething(): void
	{
		Assert::true(true);
	}
}

$test = new ExampleTest($container);
$test->run();
