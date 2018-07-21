<?php declare(strict_types = 1);

namespace App\Models;

use stdClass;

class CoinbaseTxSum
{

	/**
	 * @var int
	 */
	private $emissionAmount;

	/**
	 * @var int
	 */
	private $feeAmount;

	public function __construct()
	{
	}

	public static function fromResponse(stdClass $response): CoinbaseTxSum
	{
		$coinbaseTxSum = new CoinbaseTxSum();
		$coinbaseTxSum->emissionAmount = $response->result->emission_amount;
		$coinbaseTxSum->feeAmount = $response->result->fee_amount;

		return $coinbaseTxSum;
	}

	public function getEmissionAmount(): int
	{
		return $this->emissionAmount;
	}

	public function getFeeAmount(): int
	{
		return $this->feeAmount;
	}
}
