<?php

namespace App\Presenters;

use App\Models\RpcDaemon;
use GuzzleHttp\Exception\ConnectException;
use Nette;
use Nette\Application\UI\Form;
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

	public function beforeRender(): void
	{
		$this->template->getLatte()->addFilter('wordwrap', function (string $text, int $width): string {
			return \wordwrap($text, $width, "\n", true);
		});
		try {
			$infoData = $this->rpcDaemon->getInfo();
			$this->template->info = $infoData;
		} catch (ConnectException $e) {
			$this->setView('error');
		}
	}

	public function renderDefault(int $heightStart = 0): void
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

	public function renderDetail(string $hash): void
	{
		$this->template->block = $this->rpcDaemon->getBlockByHash($hash);
	}

	public function renderDetailByHeight(int $height): void
	{
		$this->template->block = $this->rpcDaemon->getBlockByHeight($height);
		$this->setView('detail');
	}

	protected function createComponentSearchForm(): Form
	{
		$form = new Form();
		$form->addText('search', 'Search')
			->setRequired();
		$form->addSubmit('send');
		$form->onSuccess[] = function (Form $form): void {
			$search = $form->getValues()->search;
			if (\is_numeric($search)) {
				$this->redirect('detailByHeight', $search);
			}
		};

		return $form;
	}
}
