<?php declare(strict_types = 1);

namespace App\Models\Service;

use Nette\SmartObject;

class Settings
{

	use SmartObject;

	/**
	 * @var string[]
	 */
	protected $params;

	/**
	 * @param string[] $params
	 */
	public function __construct(array $params)
	{
		$this->params = $params;
	}

	public function __get(string $name): string
	{
		return $this->params[$name];
	}
}
