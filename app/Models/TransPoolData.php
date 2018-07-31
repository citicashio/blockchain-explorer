<?php declare(strict_types = 1);

namespace App\Models;

use Nette\Utils\DateTime;
use stdClass;

class TransPoolData
{

	/**
	 * @var int
	 */
	private $blobSize;

	/**
	 * @var bool
	 */
	private $doNotRelay;

	/**
	 * @var bool
	 */
	private $doubleSpendSeen;

	/**
	 * @var int
	 */
	private $fee;

	/**
	 * @var string
	 */
	private $idHash;

	/**
	 * @var bool
	 */
	private $keptByBlock;

	/**
	 * @var int
	 */
	private $lastFailedHeight;

	/**
	 * @var string
	 */
	private $lastFailedIdHash;

	/**
	 * @var int
	 */
	private $lastRelayedTime;

	/**
	 * @var int
	 */
	private $maxUsedBlockHeight;

	/**
	 * @var string
	 */
	private $maxUsedBlockIdHash;

	/**
	 * @var int
	 */
	private $receiveTime;

	/**
	 * @var bool
	 */
	private $relayed;

	/**
	 * @var string
	 */
	private $txBlob;

	/**
	 * @var string
	 */
	private $txJson;

	/**
	 * @var DateTime
	 */
	public $created;

	public function __construct(stdClass $data)
	{
		$this->blobSize = $data->blob_size;
		$this->fee = $data->fee;
		$this->idHash = $data->id_hash;
		$this->keptByBlock = $data->kept_by_block;
		$this->lastFailedHeight = $data->last_failed_height;
		$this->lastFailedIdHash = $data->last_failed_id_hash;

		$this->maxUsedBlockHeight = $data->max_used_block_height;
		$this->maxUsedBlockIdHash = $data->max_used_block_id_hash;
		$this->receiveTime = $data->receive_time;
		$this->relayed = $data->relayed;
		//$this->doNotRelay = $data->do_not_relay;
		//$this->doubleSpendSeen = $data->double_spend_seen;
		//$this->lastRelayedTime = $data->last_relayed_time;
		//$this->txBlob = $data->tx_blob;
		$this->created = new DateTime();
		if (isset($data->tx_json)) {
			$this->txJson = new TransPoolDetailData($data->tx_json);
		}
	}

	public function getBlobSize(): int
	{
		return $this->blobSize;
	}

	public function isDoNotRelay(): bool
	{
		return $this->doNotRelay;
	}

	public function isDoubleSpendSeen(): bool
	{
		return $this->doubleSpendSeen;
	}

	public function getFee(): int
	{
		return $this->fee;
	}

	public function getIdHash(): string
	{
		return $this->idHash;
	}

	public function isKeptByBlock(): bool
	{
		return $this->keptByBlock;
	}

	public function getLastFailedHeight(): int
	{
		return $this->lastFailedHeight;
	}

	public function getLastFailedIdHash(): string
	{
		return $this->lastFailedIdHash;
	}

	public function getLastRelayedTime(): int
	{
		return $this->lastRelayedTime;
	}

	public function getMaxUsedBlockHeight(): int
	{
		return $this->maxUsedBlockHeight;
	}

	public function getMaxUsedBlockIdHash(): string
	{
		return $this->maxUsedBlockIdHash;
	}

	public function getReceiveTime(): int
	{
		return $this->receiveTime;
	}

	public function isRelayed(): bool
	{
		return $this->relayed;
	}

	public function getTxBlob(): string
	{
		return $this->txBlob;
	}

	public function getTxJson(): string
	{
		return $this->txJson;
	}

	public function getAge(): string
	{
		$timeBefore = $this->created->getTimestamp() - $this->getReceiveTime();
		return \gmstrftime('%H:%M:%S', $timeBefore);
	}
}
