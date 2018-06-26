<?php declare(strict_types = 1);

namespace App\Models;

use Nette\Utils\Json;
use stdClass;

class TransPoolDetailData
{
	public function __construct(string $data)
	{
		$this->txJson = Json::decode($data);

//		version => 2
//		unlock_time => 0
//		vin => array (11)
//		vout => array (2)
//		extra => array (33)
//		rct_signatures => stdClass #c30e
//		rctsig_prunable => stdClass #8919
//		rangeSigs => array (2)
//		MGs => array (11)

		return $this->txJson;
	}
}
