<?php
namespace App\Controller;

use Fagathe\Framework\Controller\AbstractController;
use Fagathe\Framework\Router\UrlGenerator;
use Symfony\Component\HttpFoundation\Response;

final class DefaultController extends AbstractController
{
    public function index(): Response
    {
        dd($this->generateUrl("app.blo", [
            'id' => 10, 
            'idQS' => 'params'
        ], UrlGenerator::ABSOLUTE_URL));

        return $this->json([
            'message' => [
                'type' => 'success', 
                'content' => 'It works'
            ]
        ]);
    }
}