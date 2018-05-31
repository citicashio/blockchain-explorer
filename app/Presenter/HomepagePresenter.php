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

	public function renderDefault(int $heightStart = 0)
	{
		$lastHeight = $this->rpcDaemon->getHeight() - 1;
		if ($heightStart === 0) {
			$heightStart = $lastHeight;
		}

		$blocks = $this->rpcDaemon->getBlocksByHeight($heightStart, self::ITEMS_PER_PAGE);
		//dump($blocks);
		$this->template->blocks = $blocks;
		$this->template->heightStart = $heightStart;
		$paginator = new Paginator();
		$paginator->setItemCount($lastHeight - 1);
		$paginator->setItemsPerPage(self::ITEMS_PER_PAGE);
		$paginator->setPage($heightStart);
		$paginator->setBase(1);
		$this->template->paginator = $paginator;
	}

	public function renderDetail(string $hash)
	{
		$this->template->block = $this->rpcDaemon->getBlockByHash($hash);
	}
}
