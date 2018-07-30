<?php declare(strict_types = 1);

namespace App\Models\Service;

use Nette\SmartObject;

class Settings
{
	use SmartObject;

	protected $params;

	public function __construct($params)
	{
		$this->params = $params;
	}


	public function __get($name)
	{
		return $this->params[$name];
	}
}
