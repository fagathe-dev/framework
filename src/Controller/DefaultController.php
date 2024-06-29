<?php
namespace App\Controller;

use App\Entity\User;
use App\Model\UserModel;
use Fagathe\Framework\Controller\AbstractController;
use Fagathe\Framework\Helpers\DateTimeHelperTrait;
use Fagathe\Framework\Http\Session;
use Fagathe\Framework\Logger\Logger;
use Fagathe\Framework\Router\UrlGenerator;
use Fagathe\Framework\Security\PasswordHasher;
use Symfony\Component\HttpFoundation\Response;

final class DefaultController extends AbstractController
{
    use DateTimeHelperTrait;
    public function index(array $params): Response
    {
        $logger = new Logger('test/whatever.log');
        // $prenom = $params['prenom'];
        $data = [
            'firstname' => 'Frédérick',
            'lastname' => 'AGATHE',
            'username' => 'fagathe',
            'email' => 'admin2@test.com',
            'password' => PasswordHasher::hash('admin'),
            'roles' => json_encode(['ROLE_ADMIN']),
            'confirmed' => true,
            'updated_at' => $this->__toString(),
        ];

        $logger->error('test message log with json data ' . json_encode($data, JSON_INVALID_UTF8_IGNORE));
        $session = new Session;
        $session->addFlash('success', 'User created successfully');
        dump($session->getFlashBag());
        dump($session->get('flashes'));
        dump($this->generateUrl('app.blog', ['id' => 4, 'slug' => 'mon-super-article', 'origin' => 'BACKLINK']));


        $user = (new UserModel())->findBy(['username' => 'admin2']);
        // if ($user instanceof User) {
        //     dd(PasswordHasher::verify('admin', $user->getPassword()));
        // }
        return $this->render('index.twig', compact('user'));
    }
}