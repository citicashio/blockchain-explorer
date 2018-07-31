<?php declare(strict_types = 1);

/**
 * @testCase
 */

namespace Tests\Models\Filters;

use App\Models\Filters\TemplateFilters;
use Tester\Assert;
use Tests\BaseTestCase;

$container = include __DIR__ . '/../../bootstrap.php';

class TemplateFiltersTest extends BaseTestCase
{
	private const MINUTE = 60;
	private const HOUR = 60 * 60;
	private const DAY = 60 * 60 * 24;

	public function testAmountInt(): void
	{
		Assert::same('153.553', TemplateFilters::amountInt(153553000000));
	}

	public function testFeeInt(): void
	{
		Assert::same('0.453525', TemplateFilters::feeInt(453525000));
	}

	public function testAge(): void
	{
		$timebefore = self::HOUR - 3 * self::MINUTE + 35; // 0 days 0 hour 57  minutes 35 seconds
		Assert::same('57:35', TemplateFilters::age($timebefore));

		$timebefore = self::HOUR + self::MINUTE + 35; // 0 days 1 hour 1  minutes 35 seconds
		Assert::same('01:01:35', TemplateFilters::age($timebefore));

		$timebefore = 15 * self::HOUR + 5 * self::MINUTE + 35; // 0 days 15 hour 5  minutes 35 seconds
		Assert::same('15:05:35', TemplateFilters::age($timebefore));

		$timebefore = 5 * self::DAY + 15 * self::HOUR + 10 * self::MINUTE + 15; // 5 days 15 hour 10  minutes 15 seconds
		Assert::same('05:15:10:15', TemplateFilters::age($timebefore));

		$timebefore = 120 * self::DAY + 15 * self::HOUR + 5 * self::MINUTE + 50; // 120 days 15 hour 5  minutes 50 seconds
		Assert::same('120:15:05:50', TemplateFilters::age($timebefore));
	}
}

$test = new TemplateFiltersTest($container);
$test->run();
