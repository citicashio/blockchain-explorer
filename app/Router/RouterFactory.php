<?php declare(strict_types = 1);

namespace App\Router;

use Nette;
use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class RouterFactory
{

	use Nette\StaticClass;

	public static function createRouter(): IRouter
	{
		$router = new RouteList();
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

		return $router;
	}
}
