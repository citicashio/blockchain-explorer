<?php

namespace App\Presenters;

use App\Models\RpcDaemon;
use Nette;
use Nette\Utils\Paginator;

class HomepagePresenter extends Nette\Application\UI\Presenter
{

	private const ITEMS_PER_PAGE = 30;

	/**
	 * @var RpcDaemon
	 */
	private $rpcDaemon;

	public function __construct(RpcDaemon $rpcDaemon)
	{
		parent::__construct();
		$this->rpcDaemon = $rpcDaemon;
	}

	public function beforeRender()
	{
		$infoData = $this->rpcDaemon->getInfo();
		$this->template->info = $infoData;
	}

	public function renderDefault(int $page = 0)
	{
		$actualHeight = $this->rpcDaemon->getHeight();
		//dump($height);

		$height = ($actualHeight - 1) - (self::ITEMS_PER_PAGE * $page);

		$blocks = $this->rpcDaemon->getBlocksByHeight($height, self::ITEMS_PER_PAGE);
		//dump($blocks);
		$this->template->blocks = $blocks;
		$paginator = new Paginator();
		$paginator->setItemCount($actualHeight - 1);
		$paginator->setItemsPerPage(self::ITEMS_PER_PAGE);
		$paginator->setPage($page);
		$paginator->setBase(0);
		$this->template->paginator = $paginator;
	}

	public function renderDetail(string $hash)
	{
		$this->template->block = $this->rpcDaemon->getBlockByHash($hash);
	}
}
