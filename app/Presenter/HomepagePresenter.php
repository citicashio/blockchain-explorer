<?php

namespace App\Presenters;

use App\Models\RpcDaemon;
use Nette;
use Nette\Utils\Paginator;

class HomepagePresenter extends Nette\Application\UI\Presenter
{

	private const ITEMS_PER_PAGE = 30;

	public function renderDefault(int $page = 0)
	{
		$rpcDaemon = new RpcDaemon('http://192.168.24.126', 19834);
		$infoData = $rpcDaemon->getInfo();
		$this->template->info = $infoData;
		$actualHeight = $rpcDaemon->getHeight();
		//dump($height);

		$height = ($actualHeight - 1) - (self::ITEMS_PER_PAGE * $page);

		$blocks = $rpcDaemon->getBlocksByHeight($height, self::ITEMS_PER_PAGE);
		//dump($blocks);
		$this->template->blocks = $blocks;
		$paginator = new Paginator();
		$paginator->setItemCount($actualHeight - 1);
		$paginator->setItemsPerPage(self::ITEMS_PER_PAGE);
		$paginator->setPage($page);
		$paginator->setBase(0);
		$this->template->paginator = $paginator;
	}
}
