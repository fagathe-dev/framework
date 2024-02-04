<?php

require_once '../app/globale.php';
require_once DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use Fagathe\Framework\Router\Router;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

$routes = require  DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'app/routes.php';
define('APP_ROUTES', $routes);

$request = Request::createFromGlobals();
// $filesystem = new Filesystem;
// $filesystem->appendToFile(DOCUMENT_ROOT . DIRECTORY_SEPARATOR .'logs.txt', 'Email sent to user@example.com' . "\n");
(new Router(APP_ROUTES))->match();