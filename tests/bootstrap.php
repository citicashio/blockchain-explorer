<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();

$configurator = new Nette\Configurator();
$configurator->setDebugMode(true);
$configurator->enableDebugger(__DIR__ . '/../var/log');
$configurator->setTempDirectory(__DIR__ . '/../var/temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->addDirectory(__DIR__ . '/../tests')
	->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
$configurator->addConfig(__DIR__ . '/../app/config/config.local.neon');

// Override
$configurator->addParameters([
	'appDir' => __DIR__ . '/../app/',
]);

return $configurator->createContainer();
