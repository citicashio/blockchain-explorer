<?php declare(strict_types = 1);

namespace App;

use Nette\Application\Application;
use Nette\Configurator;
use Tracy\Debugger;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Configurator();

$debugMode = $configurator->isDebugMode();
$tempDirectory = 'temp';
if (\PHP_SAPI === 'cli') {
	$debugMode = \getenv('development') === 'true';
	$tempDirectory = 'tempcli';
}

$configurator->setTimeZone('Europe/Prague');
$configurator->setDebugMode($debugMode);

$configurator->enableTracy();
$configurator->setTempDirectory(__DIR__ . '/../var/' . $tempDirectory);

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . '/../var/temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

Debugger::enable(!$debugMode, __DIR__ . '/../var/log', 'salek@citicash.io');

\set_error_handler(function ($severity, $message, $file, $line): void {
	if (!(\error_reporting() & $severity)) { // This error code is not included in error_reporting
		return;
	}
	throw new \ErrorException($message, 0, $severity, $file, $line);
});

$container = $configurator->createContainer();

/** @var Application $application */
$application = $container->getByType(Application::class);
$application->errorPresenter = 'Error';
$application->catchExceptions = $configurator->isDebugMode();


return $container;
