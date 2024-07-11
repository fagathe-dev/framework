<?php

require_once '../app/globale.php';
require_once DOCUMENT_ROOT . '/vendor/autoload.php';

use Fagathe\Framework\Env\Env;
use Fagathe\Framework\Router\Router;

$routes = require DOCUMENT_ROOT . '/app/routes.php';
define('APP_ROUTES', $routes);
Env::load();

(new Router(APP_ROUTES))->match();