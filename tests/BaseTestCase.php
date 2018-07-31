<?php declare(strict_types = 1);

namespace Tests;

use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

abstract class BaseTestCase extends TestCase
{

	/**
	 * @var Container
	 */
	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	protected function setup(): void
	{
	}

	public function run(): void
	{
		if (\defined('PHPSTAN_ACTIVE')) {
			// Error: This test forgets to execute an assertion.
			Assert::true(true);

			return;
		}
		parent::run();
	}
}
