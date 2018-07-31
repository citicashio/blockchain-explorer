<?php declare(strict_types = 1);

namespace App\Models\Service;

use Nette\SmartObject;

class Settings
{
	use SmartObject;

	/**
	 * @var mixed[]
	 */
	protected $params;

	public function __construct(array $params)
	{
		$this->params = $params;
	}


	public function __get($name): string
	{
		return $this->params[$name];
	}
}
