<?php declare(strict_types = 1);

namespace App\Models\Filters;

use Nette\Utils\Callback;

class TemplateFilters
{

	private const RAD = 1000000000;

	public function __construct()
	{
	}

	/**
	 * Method we will register as callback
	 * in method $template->addFilter().
	 *
	 * @throws \LogicException
	 */
	public function loader(string $filter): string
	{
		if (\method_exists($this, $filter) === false) {
			throw new \LogicException(\sprintf('Filter \'%s\' is not defined.', $filter));
		}

		return \call_user_func_array(Callback::closure($this, $filter), \array_slice(\func_get_args(), 1));
	}

	public static function amountInt(int $number): string
	{
		return (string)($number / self::RAD);
	}

	public static function feeInt(int $number): string
	{
		return (string)($number / self::RAD);
	}

	public static function age(int $timeBefore): string
	{
		$hour = 60 * 60;
		$day = $hour * 24;

		if ($timeBefore < $hour) {
			$result = \gmstrftime('%M:%S', $timeBefore);
		} elseif ($timeBefore < $day) {
			$result = \gmstrftime('%H:%M:%S', $timeBefore);
		} else {
			$days = \floor($timeBefore / $day);
			$daysString = (string)$days < 10 ? '0' . $days : $days;
			$inday = $timeBefore % $day;
			$result = $daysString . ':' . \gmstrftime('%H:%M:%S', $inday);
		}

		return $result;
	}

	public function hardSpace(string $string): string
	{
		return \str_replace(' ', '&nbsp;', $string);
	}
}
