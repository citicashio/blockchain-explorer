<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Models\RpcDaemon;
use Nette\Application\BadRequestException;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Json;
use Nette\Utils\Paginator;

/**
 * @property-read Template $template
 */
class HomepagePresenter extends BasePresenter
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

	public function beforeRender(): void
	{
		$infoData = $this->rpcDaemon->getInfo();
		$this->template->info = $infoData;
	}

	public function renderDefault(int $heightStart = 0): void
	{
		$lastHeight = $this->rpcDaemon->getHeight() - 1;
		if ($heightStart === 0) {
			$heightStart = $lastHeight;
		}

		$transactionsPoolData = $this->rpcDaemon->getTransactionPool();
		$this->template->tpData = $transactionsPoolData->getAllData();
		$blocks = $this->rpcDaemon->getBlocksByHeight($heightStart, self::ITEMS_PER_PAGE);
		$this->template->blocks = $blocks;
		$this->template->heightStart = $heightStart;
		$paginator = new Paginator();
		$paginator->setItemCount($lastHeight - 1);
		$paginator->setItemsPerPage(self::ITEMS_PER_PAGE);
		$paginator->setPage($heightStart);
		$paginator->setBase(1);
		$this->template->paginator = $paginator;
	}

	public function renderDetail(string $hash): void
	{
		try {
			$this->template->block = $this->rpcDaemon->getBlockByHash($hash);
		} catch (BadRequestException $e) {
			$this->redirect('transaction', $hash);
		}
	}

	public function renderDetailByHeight(int $height): void
	{
		$this->template->block = $this->rpcDaemon->getBlockByHeight($height);
		$this->setView('detail');
	}

	public function renderTransaction(string $hash): void
	{
		$transactions = $this->rpcDaemon->getTransactions([$hash]);
		$this->template->transactions = $transactions;
		$this->template->pretty = Json::encode($transactions->getData(), Json::PRETTY);
	}
}
