<?php declare(strict_types = 1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Presenter;

/**
 * @property-read Nette\Application\UI\ITemplate $template
 */
class Error4xxPresenter extends BasePresenter
{

	public function startup(): void
	{
		parent::startup();
		$request = $this->getRequest();
		if ($request !== null && $request->isMethod(Nette\Application\Request::FORWARD)) {
			return;
		}

		$this->error();
	}

	public function renderDefault(Nette\Application\BadRequestException $exception): void
	{
		// Load template 403.latte or 404.latte or ... 4xx.latte.
		$file = \sprintf('%s/../templates/Error/%s.latte', __DIR__, $exception->getCode());

		if (\is_file($file) === false) {
			$file = __DIR__ . '../templates/Error/4xx.latte';
		}

		$this->template->setFile($file);
	}
}
