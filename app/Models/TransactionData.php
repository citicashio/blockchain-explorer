<?php declare(strict_types = 1);

namespace App\Models;

use Nette\Utils\Json;
use stdClass;

class TransactionData
{

	/**
	 * @var stdClass
	 */
	private $data;

	public function __construct()
	{
	}

	public static function fromResponse(stdClass $response): TransactionData
	{
		//\dump($response);
		$data = $response->as_json;
		$data = Json::decode($data);
		$data->block_height = $response->block_height;
		$data->in_pool = $response->in_pool;
		$data->tx_mixin = $response->tx_mixin ?? '?N/A';
		$data->tx_size = $response->tx_size ?? '?N/A';
		if (isset($response->output_indices)) {
			$data->output_indices = $response->output_indices;
		}
		if (isset($response->tx_amount)) {
			$data->tx_amount = $response->tx_amount;
		} else {
			$data->tx_amount = null;
		}
		$data->tx_hash = $response->tx_hash;
		//dump($data);

		$blockData = new TransactionData();
		$blockData->data = $data;

		return $blockData;
	}

	public function setData(stdClass $data): void
	{
		$this->data = $data;
	}

	public function getData(): stdClass
	{
		return $this->data;
	}
}
