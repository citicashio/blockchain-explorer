<?php declare(strict_types = 1);

namespace App\Forms;

use Nette\Application\UI\Form;
use Nette\Forms\ISubmitterControl;

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

	public function create(callable $onSuccess, callable $onClear): Form
	{
		$form = $this->formFactory->create();
		$form->getElementPrototype()
			->addAttributes(['class' => 'form']);
		$form->addText('viewKey', 'View key')
			->setRequired();
		$form->addSubmit('send', 'Send');
		$form->addSubmit('reset', 'Reset');

		$form->onSuccess[] = function (Form $form, array $values) use ($onSuccess, $onClear): void {
			$submitterControl = $form->isSubmitted();
			if ($submitterControl instanceof ISubmitterControl && $submitterControl->getValue() === 'Reset') {
				$onClear();
			}
			$onSuccess($values['viewKey']);
		};

		return $form;
	}
}
