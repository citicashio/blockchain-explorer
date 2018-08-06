<?php declare(strict_types = 1);

if (\PHP_SAPI === 'cli') {
	die('run app/console.php');
}

ob_start(); // prevent ini_set(): Headers already sent.

$container = require __DIR__ . '/../app/bootstrap.php';

/** @var \Nette\Application\Application $app */
$app = $container->getByType(Nette\Application\Application::class);

$app->run();
