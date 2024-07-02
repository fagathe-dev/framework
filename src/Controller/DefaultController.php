<?php
namespace App\Controller;

use App\Model\UserModel;
use Fagathe\Framework\Controller\AbstractController;
use Fagathe\Framework\Helpers\DateTimeHelperTrait;
use Symfony\Component\HttpFoundation\Response;

final class DefaultController extends AbstractController
{
    use DateTimeHelperTrait;
    public function index(array $params): Response
    {
        $userModel = new UserModel();
        
        return $this->render('index.twig');
    }
}