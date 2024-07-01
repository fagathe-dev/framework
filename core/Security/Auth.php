<?php
namespace Fagathe\Framework\Security;

use App\Entity\User;
use App\Model\UserModel;
use Fagathe\Framework\Http\Session;

final class Auth
{

    private const AUTHENTICATION_ERROR = 'authentication_error';
    private const MISSING_DATA_MESSAGE = 'Le nom d\'utilisateur et le mot de passe sont obkigatoires.';
    private const INVALID_CREDENTIALS = 'Identifiants incorrects.';
    private UserModel $userModel;
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
        $this->userModel = new UserModel();
    }
    public function login(string $username, string $password): void
    {
        if (($username === '' && !isset($username)) || ($password === '' && !isset($password))) {

        }

        $username = trim($username);
        $password = trim($password);

        $user = $this->userModel->findOneBy(compact('username'));
        if ($user instanceof User) {

        } else {
            $this->errorMessage(self::INVALID_CREDENTIALS);
        }

    }

    private function errorMessage(string $message): void
    {
        $this->session->addFlash('authentication_error', [
            'type' => self::AUTHENTICATION_ERROR,
            'message' => $message,
        ]);
    }

    public function remember_me(): void
    {
    }

    public static function logout(): void
    {
    }
}
