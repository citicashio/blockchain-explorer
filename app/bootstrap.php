<?php

namespace App;

use Nette\Configurator;
use Tracy\Debugger;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Configurator;

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

$container = $configurator->createContainer();

return $container;
