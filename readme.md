Citicash Blockchain explorer
=================

Written in PHP and Nette Framework.


Requirements
------------

PHP 7.1 or higher.


Installation
------------

	git clone https://github.com/citicashio/blockchain-explorer.git
	cd blockchain-explorer
	composer install


Make directories `temp/` and `log/` writable.


Web Server Setup
----------------

The simplest way to get started is to start the built-in PHP server in the root directory of your project:

	php -S localhost:8000 -t www

Then visit `http://localhost:8000` in your browser to see the welcome page.

For Apache or Nginx, setup a virtual host to point to the `www/` directory of the project and you
should be ready to go.

**It is CRITICAL that whole `app/`, `log/` and `temp/` directories are not accessible directly
via a web browser. See [security warning](https://nette.org/security-warning).**

Notice: Composer PHP version
----------------------------
This project forces `PHP 7.1` as your PHP version for Composer packages. If you have newer version on production you should change it in `composer.json`.
```json
"config": {
	"platform": {
		"php": "7.2"
	}
}
```
