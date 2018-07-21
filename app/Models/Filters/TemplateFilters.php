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
		return (string) ($number / self::RAD);
	}

	public static function feeInt(int $number): string
	{
		return (string) ($number / self::RAD);
	}
}
