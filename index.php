<?php 

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

$name = $request->query->get('name', 'World');

$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => dirname(__DIR__) . '/var/cache',
]);

// the URI being requested (e.g. /about) minus any query parameters
$request->getPathInfo();

// retrieves GET and POST variables respectively
$request->query->get('foo');
$request->getPayload()->get('bar', 'default value if bar does not exist');

// retrieves SERVER variables
$request->server->get('HTTP_HOST');

// retrieves an instance of UploadedFile identified by foo
$request->files->get('foo');

// retrieves a COOKIE value
$request->cookies->get('PHPSESSID');

// retrieves a HTTP request header, with normalized, lowercase keys
$request->headers->get('host');
$request->headers->get('content-type');

$request->getMethod();    // GET, POST, PUT, DELETE, HEAD
$request->getLanguages(); // an array of languages the client accepts