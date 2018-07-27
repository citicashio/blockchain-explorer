<?php declare(strict_types = 1);

namespace App\Models;

use GuzzleHttp\Client;
use Nette\Application\BadRequestException;
use Nette\Utils\Json;
use stdClass;

class RpcDaemon
{

	/**
	 * @var string
	 */
	private $host;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var Client
	 */
	private $client;

	public function __construct(string $host, int $port)
	{
		$this->host = $host;
		$this->port = $port;
		$this->client = new Client(
			[
				'base_uri' => $this->host . ':' . $this->port,
				'timeout' => 15.0,
			]
		);
	}

	public function getInfo(): InfoData
	{
		$body = [
			'method' => 'get_info',
		];

		$response = $this->getResponse('/json_rpc', $body);

		return InfoData::fromResponse($response);
	}

	public function getHeight(): int
	{
		$body = [
			'method' => 'getblockcount',
		];
		$response = $this->getResponse('/json_rpc', $body);

		return (int)$response->result->count;
	}

	public function getBlockByHeight(int $height): BlockData
	{
		$body = [
			'method' => 'getblock',
			'params' => [
				'height' => $height,
			],
		];

		$response = $this->getResponse('/json_rpc', $body);

		return BlockData::fromResponse($response);
	}

	/**
	 * @deprecated fees are in the `getBlockByHeight`
	 */
	public function getCoinbaseTxSum(int $height): CoinbaseTxSum
	{
		$body = [
			'method' => 'get_coinbase_tx_sum',
			'params' => [
				'height' => $height,
				'count' => 1,
			],
		];

		$response = $this->getResponse('/json_rpc', $body);

		return CoinbaseTxSum::fromResponse($response);
	}

	public function getBlockByHash(string $hash): BlockData
	{
		$body = [
			'method' => 'getblock',
			'params' => [
				'hash' => $hash,
			],
		];

		$response = $this->getResponse('/json_rpc', $body);

		return BlockData::fromResponse($response);
	}

	public function getTransactionPool(): TransactionsPoolData
	{
		$body = [
			'decode_as_json' => true,
		];
		$response = $this->getResponse('/get_transaction_pool', $body);

		return new TransactionsPoolData($response);
	}

	/**
	 * @return BlockData[]
	 */
	public function getBlocksByHeight(int $heightStart, int $limit): array
	{
		$response = [];
		for ($i = 0; $i < $limit; $i++) {
			$actualHeightPointer = $heightStart - $i;
			if ($actualHeightPointer < 1) {
				break;
			}
			$response[$actualHeightPointer] = $this->getBlockByHeight($actualHeightPointer);
		}

		return $response;
	}

	/**
	 * @param string[] $transactions
	 * @return TransactionData[]
	 */
	public function getTransactions(array $transactions): array
	{
		$body = [
			'txs_hashes' => $transactions,
			'decode_as_json' => true,
		];

		$response = $this->getResponse('/gettransactions', $body);
		if (isset($response->missed_tx)) {
			throw new BadRequestException('Missed_tx');
		}

		if (isset($response->status) && ($response->status === 'Failed to parse hex representation of transaction hash')) {
			throw new BadRequestException($response->status);
		}

		if (isset($response->status) && ($response->status !== 'OK')) {
			throw new BadRequestException($response->status);
		}

		$return = [];
		foreach ($response->txs as $tx) {
			$return[] = TransactionData::fromResponse($tx);
		}

		return $return;
	}

	/**
	 * @param string $path
	 * @param mixed[] $body
	 * @return stdClass
	 * @throws BadRequestException
	 */
	private function getResponse(string $path, array $body): stdClass
	{
		$options = ['body' => Json::encode($body)];
		$request = $this->client->get($path, $options);
		$response = Json::decode($request->getBody()->getContents());

		if (isset($response->error) && isset($response->error->message)) {
			throw new BadRequestException($response->error->message);
		}

		return $response;
	}
}
