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
	private $majorVersion;

	/**
	 * @var int
	 */
	private $minorVersion;

	/**
	 * @var int
	 */
	private $nonce;

	/**
	 * @var bool
	 */
	private $orphanStatus;

	/**
	 * @var string
	 */
	private $prevHash;

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

	/**
	 * @var string
	 */
	private $prevId;

	/**
	 * @var int
	 */
	private $minerTxVersion;

	/**
	 * @var int
	 */
	private $minerTxUnlockTime;

	/**
	 * @var string[]
	 */
	private $minerTxVin = [];

	/**
	 * @var string[]
	 */
	private $minerTxVout = [];

	/**
	 * @var int[]
	 */
	private $minerTxExtra = [];

	/**
	 * @var string
	 */
	private $minerTxSignatures;

	/**
	 * @var string
	 */
	private $rawResultJsonRctSignatures;

	/**
	 * @var int
	 */
	private $rawResultJsonRctSignaturesType;

	/**
	 * @var int
	 */
	private $fee;

	/**
	 * @var int
	 */
	private $blockSize;

	/**
	 * @var TransactionData[]
	 */
	private $transactions = [];

	public function __construct()
	{
	}

	public static function fromResponse(stdClass $response): BlockData
	{
		//dump($response);
		$header = $response->result->block_header;

		$blockData = new BlockData();
		$blockData->depth = $header->depth;
		$blockData->difficulty = $header->difficulty;
		$blockData->hash = $header->hash;
		$blockData->height = $header->height;
		$blockData->majorVersion = $header->major_version;
		$blockData->minorVersion = $header->minor_version;
		$blockData->nonce = $header->nonce;
		$blockData->orphanStatus = $header->orphan_status;
		$blockData->prevHash = $header->prev_hash;
		$blockData->reward = $header->reward;
		$blockData->timestamp = $header->timestamp;
		$blockData->dateTime = DateTime::from($header->timestamp);
		$blockData->fee = $header->fee;
		$blockData->blockSize = $header->block_size;

		$blockData->blob = $response->result->blob;
		$blockData->raw = $response;

		$json = Json::decode($response->result->json);
		$blockData->txHashes = $json->tx_hashes;
		$blockData->prevId = $json->prev_id;
		$blockData->minerTxVersion = $json->miner_tx->version;
		$blockData->minerTxUnlockTime = $json->miner_tx->unlock_time;
		$blockData->minerTxVin = $json->miner_tx->vin;
		$blockData->minerTxVout = $json->miner_tx->vout;
		$blockData->minerTxExtra = $json->miner_tx->extra;
		$blockData->rawResultJsonRctSignatures = isset($json->miner_tx->rct_signatures) ? 'yes' : 'no';
		$blockData->rawResultJsonRctSignaturesType = $json->miner_tx->rct_signatures->type;

		//$blockData->minerTxSignatures = $json->miner_tx->signatures;

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
		return $this->majorVersion;
	}

	public function getMinorVersion(): int
	{
		return $this->minorVersion;
	}

	public function getNonce(): int
	{
		return $this->nonce;
	}

	public function isOrphanStatus(): bool
	{
		return $this->orphanStatus;
	}

	public function getPrevHash(): string
	{
		return $this->prevHash;
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

	/**
	 * @return string[]
	 */
	public function getTxHashes(): array
	{
		return $this->txHashes;
	}

	public function getTxCount(): int
	{
		return \count($this->txHashes);
	}

	public function getBlob(): string
	{
		return $this->blob;
	}

	public function getRaw(): stdClass
	{
		return $this->raw;
	}

	public function getPrevId(): string
	{
		return $this->prevId;
	}

	public function getMinerTxVersion(): int
	{
		return $this->minerTxVersion;
	}

	public function getMinerTxUnlockTime(): int
	{
		return $this->minerTxUnlockTime;
	}

	/**
	 * @return string[]
	 */
	public function getMinerTxVin(): array
	{
		return $this->minerTxVin;
	}

	public function getCountOfMinerTxVin(): int
	{
		return \count($this->minerTxVin);
	}

	/**
	 * @return string[]
	 */
	public function getMinerTxVout(): array
	{
		return $this->minerTxVout;
	}

	public function getCountOfMinerTxVout(): int
	{
		return \count($this->minerTxVout);
	}

	/**
	 * @return int[]
	 */
	public function getMinerTxExtra(): array
	{
		return $this->minerTxExtra;
	}

	public function getMinerTxSignatures(): string
	{
		return $this->minerTxSignatures;
	}

	/**
	 * @todo compute javascript on frontend
	 */
	public function getAge(): string
	{
		$now = new DateTime();
		$timeBefore = $now->getTimestamp() - $this->getTimestamp();

		return \gmstrftime('%H:%M:%S', $timeBefore);
	}

	public function getRawResultJsonRctSignatures(): string
	{
		return $this->rawResultJsonRctSignatures;
	}

	public function getRawResultJsonRctSignaturesType(): int
	{
		return $this->rawResultJsonRctSignaturesType;
	}

	public function getFee(): int
	{
		return $this->fee;
	}

	public function getBlockSize(): int
	{
		return $this->blockSize;
	}

	public function setTransactions(array $transactions)
	{
		$this->transactions = $transactions;
	}

	public function getTransactions(): array
	{
		return $this->transactions;
	}
}
