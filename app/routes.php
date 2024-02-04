<?php

use App\Controller\DefaultController;
use Fagathe\Framework\Router\Route;

return [
    new Route('/', name: 'app.default', action: DefaultController::class."@index"),
    new Route('/blog/:id', name: 'app.blog', action: DefaultController::class."@index"),
];