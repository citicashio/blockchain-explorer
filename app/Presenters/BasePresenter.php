<?php declare(strict_types = 1);

namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\ITemplate;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;

abstract class BasePresenter extends Presenter
{

	protected function createComponentSearchForm(): Form
	{
		$form = new Form();
		$form->addText('search', 'Search')
			->setRequired();
		$form->addSubmit('send');
		$form->onSuccess[] = function (Form $form, array $values): void {
			$search = $values['search'];
			if (\is_numeric($search)) {
				$this->redirect('detailByHeight', $search);
			}
			$this->redirect('detail', $search);
		};

		return $form;
	}

	protected function createTemplate(): ITemplate
	{
		/** @var Template $template */
		$template = parent::createTemplate();
		$template->getLatte()->addFilter('wordwrap', function (string $text, int $width): string {
			return \wordwrap($text, $width, "\n", true);
		});
		$template->now = new \DateTime();
		$nowUtc = new \DateTime('now', new \DateTimeZone('UTC'));
		$template->nowUtc = $nowUtc->format(\DateTime::RFC850);

		return $template;
	}
}
