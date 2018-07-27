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
		//dump($response);
		$data = $response->txs[0]->as_json;
		$data = Json::decode($data);
		$data->block_height = $response->txs[0]->block_height;
		$data->in_pool = $response->txs[0]->in_pool;
		$data->output_indices = $response->txs[0]->output_indices;
		$data->tx_hash = $response->txs[0]->tx_hash;
		dump($data);

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
