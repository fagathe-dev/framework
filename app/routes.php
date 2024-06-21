<?php

use App\Controller\DefaultController;
use Fagathe\Framework\Router\Route;

return [
    new Route('/', name: 'app.default', action: DefaultController::class."@index", methods: ["GET", 'POST']),
    new Route('/blog/{prenom}', name: 'app.blog', action: DefaultController::class."@index", methods: ["GET", 'POST']),
];