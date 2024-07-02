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
use Fagathe\Framework\Token\Token;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $user = (new UserModel())->findOneBy(['username' => 'admin2']);
        $toto = ['id', 'desc'];

        $logger->error('test message log with json data ' . json_encode($data, JSON_INVALID_UTF8_IGNORE));
        $session = new Session;
        $session->addFlash('success', 'User created successfully');
        $hash = PasswordHasher::hash('admin');
        $encoded = base64_encode($hash);
        dump($encoded, base64_decode($encoded));
        dump($session->getFlashBag());
        dump(Token::generate(length: 60, unique: true));
        dump($session->get('flashes'));
        dump($this->generateUrl('app.blog', ['id' => 4, 'slug' => 'mon-super-article', 'origin' => 'BACKLINK']));


        // if ($user instanceof User) {
        //     dd(PasswordHasher::verify('admin', $user->getPassword()));
        // }
        return $this->render('index.twig', compact('user'));
    }
}