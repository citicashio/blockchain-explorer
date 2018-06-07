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
	 * @param mixed[] $body
	 */
	private function getResponse(string $path, array $body): stdClass
	{
		$options = ['body' => Json::encode($body)];
		dump($options);
		$request = $this->client->get($path, $options);

		$response = Json::decode($request->getBody()->getContents());

		if (isset($response->error) && isset($response->error->message)) {
			throw new BadRequestException($response->error->message);
		}

		return $response;
	}
}

