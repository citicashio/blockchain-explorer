<?php declare(strict_types = 1);

namespace App\Models;

use Nette\Utils\Json;
use stdClass;

class TransPoolDetailData
{

	/**
	 * @var stdClass
	 */
	private $txJson;

	public function __construct(string $data)
	{
		$this->txJson = Json::decode($data);
	}

	public function getData(): stdClass
	{
		return $this->txJson;
	}
}
