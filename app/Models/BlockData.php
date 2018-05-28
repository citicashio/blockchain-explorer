<?php declare(strict_types = 1);

namespace App\Models;

use Nette\Utils\DateTime;
use Nette\Utils\Json;
use stdClass;

class BlockData
{

	/**
	 * @var int
	 */
	private $depth;

	/**
	 * @var int
	 */
	private $difficulty;

	/**
	 * @var string
	 */
	private $hash;

	/**
	 * @var int
	 */
	private $height;

	/**
	 * @var int
	 */
	private $major_version;

	/**
	 * @var int
	 */
	private $minor_version;

	/**
	 * @var int
	 */
	private $nonce;

	/**
	 * @var bool
	 */
	private $orphan_status;

	/**
	 * @var string
	 */
	private $prev_hash;

	/**
	 * @var int
	 */
	private $reward;

	/**
	 * @var int
	 */
	private $timestamp;

	/**
	 * @var \DateTime
	 */
	private $dateTime;

	/**
	 * @var string
	 */
	private $blob;

	/**
	 * @var stdClass
	 */
	private $raw;

	/**
	 * @var string[]
	 */
	private $txHashes = [];

	public function __construct()
	{

	}

	public static function fromResponse(stdClass $response)
	{
		//dump($response);

		$header = $response->result->block_header;

		$blockData = new BlockData();
		$blockData->depth = $header->depth;
		$blockData->difficulty = $header->difficulty;
		$blockData->hash = $header->hash;
		$blockData->height = $header->height;
		$blockData->major_version = $header->major_version;
		$blockData->minor_version = $header->minor_version;
		$blockData->nonce = $header->nonce;
		$blockData->orphan_status = $header->orphan_status;
		$blockData->prev_hash = $header->prev_hash;
		$blockData->reward = $header->reward;
		$blockData->timestamp = $header->timestamp;
		$blockData->dateTime = DateTime::from($header->timestamp);
		$blockData->blob = $response->result->blob;
		$blockData->raw = $response;

		$json = Json::decode($response->result->json);
		//dump($json);
		$blockData->txHashes = $json->tx_hashes;

		return $blockData;
	}

	public function getDepth(): int
	{
		return $this->depth;
	}

	public function getDifficulty(): int
	{
		return $this->difficulty;
	}

	public function getHash(): string
	{
		return $this->hash;
	}

	public function getHeight(): int
	{
		return $this->height;
	}

	public function getMajorVersion(): int
	{
		return $this->major_version;
	}

	public function getMinorVersion(): int
	{
		return $this->minor_version;
	}

	public function getNonce(): int
	{
		return $this->nonce;
	}

	public function isOrphanStatus(): bool
	{
		return $this->orphan_status;
	}

	public function getPrevHash(): string
	{
		return $this->prev_hash;
	}

	public function getReward(): int
	{
		return $this->reward;
	}

	public function getTimestamp(): int
	{
		return $this->timestamp;
	}

	public function getDateTime(): \DateTime
	{
		return $this->dateTime;
	}

	public function getTxHashes(): array
	{
		return $this->txHashes;
	}
}
