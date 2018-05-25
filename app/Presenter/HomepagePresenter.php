<?php

namespace App\Presenters;

use App\Models\RpcDaemon;
use Nette;

class HomepagePresenter extends Nette\Application\UI\Presenter
{
	public function renderDefault()
	{
		$rpcDaemon = new RpcDaemon('http://192.168.24.126', 19834);
		$height = $rpcDaemon->getHeight();
		dump($height);

		$block = $rpcDaemon->getBlockByHeight($height-1);
		dump($block);
	}
}
