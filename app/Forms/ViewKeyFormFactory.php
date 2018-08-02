<?php declare(strict_types = 1);

namespace App\Forms;

use Nette\Application\UI\Form;

class ViewKeyFormFactory
{

	/**
	 * @var FormFactory
	 */
	private $formFactory;

	public function __construct(FormFactory $formFactory)
	{
		$this->formFactory = $formFactory;
	}

	public function create(callable $onSuccess): Form
	{
		$form = $this->formFactory->create();
		$form->getElementPrototype()
			->addAttributes(['class' => 'form']);
		$form->addText('viewKey', 'View key')
			->setRequired();
		$form->addSubmit('send', 'Send');
		$form->addSubmit('reset', 'Reset');

		$form->onSuccess[] = function (Form $form, array $values) use ($onSuccess): void {
			if ($form->isSubmitted()->getValue() === 'Reset') {
				$form->getPresenter()->redirect('this');
			}
			$onSuccess($values['viewKey']);
		};

		return $form;
	}
}
