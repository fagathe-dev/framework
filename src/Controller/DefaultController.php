<?php
namespace App\Controller;

use App\Entity\User;
use App\Model\UserModel;
use Fagathe\Framework\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class DefaultController extends AbstractController
{
    public function index(array $params): Response
    { 
        $prenom = $params['prenom'];
        $data = [
            'firstname' => 'Frédérick',
            'lastname' => 'AGATHE',
            'username' => 'fagathe',
            'email' => 'fagathe@meilleurtaux.com',
            'password' => password_hash('password', PASSWORD_ARGON2ID),
            'roles' => json_encode(['ROLE_ADMIN']),
            'confirmed' => null,
            'created_at' => (new \DateTimeImmutable(timezone: new \DateTimeZone(DEFAULT_DATE_TIMEZONE)))->format('Y-m-d H:i:s'),
        ];
        dump((new UserModel())->findAll());
        return $this->render('index.twig', compact('prenom'));
    }
}