<?php declare(strict_types = 1);

namespace App\Models;

use stdClass;

class InfoData
{

	/**
	 * @var int
	 */
	private $altBlocksCount;

	/**
	 * @var int
	 */
	private $cumulativeDifficulty;

	/**
	 * @var int
	 */
	private $difficulty;

	/**
	 * @var int
	 */
	private $greyPeerlistSize;

	/**
	 * @var int
	 */
	private $height;

	/**
	 * @var int
	 */
	private $incomingConnectionsCount;

	/**
	 * @var int
	 */
	private $outgoingConnectionsCount;

	/**
	 * @var string
	 */
	private $status;

	/**
	 * @var int
	 */
	private $target;

	/**
	 * @var int
	 */
	private $testnet;

	/**
	 * @var string
	 */
	private $topBlockHash;

	/**
	 * @var int
	 */
	private $txCount;

	/**
	 * @var int
	 */
	private $txPoolSize;

	/**
	 * @var int
	 */
	private $whitePeerlistSize;

	/**
	 * @var int
	 */
	private $targetHeight;

	/**
	 * @var int
	 */
	private $hashRate;

	public function __construct()
	{

	}

	public static function fromResponse(stdClass $response)
	{
		$result = $response->result;

		$infoData = new InfoData();
		$infoData->altBlocksCount = $result->alt_blocks_count;
		$infoData->cumulativeDifficulty = $result->cumulative_difficulty;
		$infoData->difficulty = $result->difficulty;
		$infoData->greyPeerlistSize = $result->grey_peerlist_size;
		$infoData->height = $result->height;
		$infoData->incomingConnectionsCount = $result->incoming_connections_count;
		$infoData->outgoingConnectionsCount = $result->outgoing_connections_count;
		$infoData->status = $result->status;
		$infoData->target = $result->target;
		$infoData->targetHeight = $result->target_height;
		$infoData->testnet = $result->testnet;
		$infoData->topBlockHash = $result->top_block_hash;
		$infoData->txCount = $result->tx_count;
		$infoData->txPoolSize = $result->tx_pool_size;
		$infoData->whitePeerlistSize = $result->white_peerlist_size;
		$infoData->hashRate = $result->hash_rate;

		return $infoData;
	}

	public function getAltBlocksCount(): int
	{
		return $this->altBlocksCount;
	}

	public function getCumulativeDifficulty(): int
	{
		return $this->cumulativeDifficulty;
	}

	public function getDifficulty(): int
	{
		return $this->difficulty;
	}

	public function getGreyPeerlistSize(): int
	{
		return $this->greyPeerlistSize;
	}

	public function getHeight(): int
	{
		return $this->height;
	}

	public function getIncomingConnectionsCount(): int
	{
		return $this->incomingConnectionsCount;
	}

	public function getOutgoingConnectionsCount(): int
	{
		return $this->outgoingConnectionsCount;
	}

	public function getStatus(): string
	{
		return $this->status;
	}

	public function getTarget(): int
	{
		return $this->target;
	}

	public function getTestnet(): int
	{
		return $this->testnet;
	}

	public function getTopBlockHash(): string
	{
		return $this->topBlockHash;
	}

	public function getTxCount(): int
	{
		return $this->txCount;
	}

	public function getTxPoolSize(): int
	{
		return $this->txPoolSize;
	}

	public function getWhitePeerlistSize(): int
	{
		return $this->whitePeerlistSize;
	}

	public function getHashRate(): int
	{
		return $this->hashRate;
	}

	public function getTargetHeight(): int
	{
		return $this->targetHeight;
	}
}
