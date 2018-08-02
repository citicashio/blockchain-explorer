<?php declare(strict_types = 1);

namespace App\Forms;

use Nette\Application\UI\Form;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\SmartObject;

class FormFactory
{

	use SmartObject;

	public function __construct()
	{
	}

	public function create(): Form
	{
		$form = new Form();
		$form->addProtection('Timeout expired, submit form again');
		$form->onRender[] = 'App\Forms\FormFactory::makeBootstrap4';

		return $form;
	}

	public static function makeBootstrap4(Form $form): void
	{
		/** @var DefaultFormRenderer $renderer */
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = null;
		$renderer->wrappers['pair']['container'] = 'div class="is-floating-label"';
		$renderer->wrappers['pair']['.error'] = 'has-danger';
		$renderer->wrappers['control']['description'] = 'span class=form-text';
		$renderer->wrappers['control']['errorcontainer'] = 'div class=form-control-feedback';

		foreach ($form->getControls() as $control) {
			$type = $control->getOption('type');
			if ($type === 'button') {
				$control->getControlPrototype()->addClass('btn red-button');
			} elseif (\in_array($type, ['text', 'textarea', 'select'], true)) {
				$control->getControlPrototype()->addClass('form-control');
			} elseif ($type === 'file') {
				$control->getControlPrototype()->addClass('form-control-file');
			} elseif (\in_array($type, ['checkbox', 'radio'], true)) {
				if ($control instanceof \Nette\Forms\Controls\Checkbox) {
					$control->getLabelPrototype()->addClass('form-check-label');
				} else {
					$control->getItemLabelPrototype()->addClass('form-check-label');
				}
				$control->getControlPrototype()->addClass('form-check-input');
				$control->getSeparatorPrototype()->setName('div')->addClass('form-check');
			}
		}
	}
}
