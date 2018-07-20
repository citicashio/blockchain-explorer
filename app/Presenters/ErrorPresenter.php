<?php declare(strict_types = 1);

namespace App\Presenters;

use Nette;
use Nette\Application\IResponse;
use Nette\Application\Responses;
use Tracy\ILogger;

/**
 * @property-read Nette\Application\UI\ITemplate $template
 */
class ErrorPresenter extends Nette\Application\UI\Presenter
{

	use Nette\SmartObject;

	/**
	 * @var ILogger
	 */
	private $logger;

	public function __construct(ILogger $logger)
	{
		parent::__construct();
		$this->logger = $logger;
	}

	public function run(Nette\Application\Request $request): IResponse
	{
		$this->template->hostname = \gethostname();

		$e = $request->getParameter('exception');
		if ($e instanceof Nette\Application\BadRequestException) {
			// $this->logger->log("HTTP code {$e->getCode()}: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}", 'access');

			$file = \sprintf('%s/../templates/Error/%s.latte', __DIR__, $e->getCode());

			if (\is_file($file) === false) {
				$file = __DIR__ . '../templates/Error/4xx.latte';
			}

			$this->template->setFile($file);
			$source = $this->template->render();

			return new Responses\TextResponse($source);
		}

		$this->logger->log($e, ILogger::EXCEPTION);

		return new Responses\CallbackResponse(function (): void {
			include __DIR__ . '/../templates/Error/500.phtml';
		});
	}

	/**
	 * @return string[]
	 */
	public function formatLayoutTemplateFiles(): array
	{
		return [
			__DIR__ . '/../templates/@layout.latte',
		];
	}
}
