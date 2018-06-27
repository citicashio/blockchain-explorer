<?php declare(strict_types = 1);

namespace App\Models;

use stdClass;

class TransactionsPoolData
{

	/**
	 * @var TransPoolData[]
	 */
	private $tpData = [];

	public function __construct(stdClass $response)
	{
		if (isset($response->transactions)) {
			foreach ($response->transactions as $transactions) {
				$this->tpData[] = new TransPoolData($transactions);
			}
		}
	}

	/**
	 * @return TransPoolData[]
	 */
	public function getAllData(): array
	{
		return $this->tpData;
	}
}
