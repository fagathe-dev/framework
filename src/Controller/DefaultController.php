<?php
namespace App\Controller;

use App\Entity\User;
use App\Model\UserModel;
use Fagathe\Framework\Controller\AbstractController;
use Fagathe\Framework\Helpers\DateTimeHelperTrait;
use Fagathe\Framework\Http\Session;
use Fagathe\Framework\Logger\Logger;
use Fagathe\Framework\Router\UrlGenerator;
use Fagathe\Framework\Security\Auth;
use Fagathe\Framework\Security\PasswordHasher;
use Fagathe\Framework\Token\Token;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DefaultController extends AbstractController
{
    use DateTimeHelperTrait;
    public function index(array $params): Response
    {
        return $this->render('index.twig');
    }
}