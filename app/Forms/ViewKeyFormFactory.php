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

		$form->onSuccess[] = function (Form $form, array $values) use ($onSuccess): void {
			$onSuccess($values['viewKey']);
		};

		return $form;
	}
}
