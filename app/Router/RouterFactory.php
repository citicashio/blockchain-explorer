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
		$router[] = new Route('index.php', 'Homepage:default', IRouter::ONE_WAY);
		$router[] = new Route('/block/<hash>', 'Homepage:detail');
		$router[] = new Route('/height/<height>', 'Homepage:detailByHeight');
		$router[] = new Route('/tx/<hash>', 'Homepage:transaction');
		$router[] = new Route('/api/info', 'Homepage:info');

		$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

		return $router;
	}
}
