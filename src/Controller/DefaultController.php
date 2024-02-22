<?php
namespace App\Controller;

use Fagathe\Framework\Controller\AbstractController;
use Fagathe\Framework\Database\Connection;
use Fagathe\Framework\Database\Database;
use Fagathe\Framework\Database\Table;
use Symfony\Component\HttpFoundation\Response;

final class DefaultController extends AbstractController
{
    public function index(): Response
    {
        (new Table('user'))->generate();

        return $this->json([
            'message' => [
                'type' => 'success', 
                'content' => 'It works'
            ]
        ]);
    }
}