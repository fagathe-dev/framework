<?php
namespace Fagathe\Framework\Security;

use Fagathe\MonSite\Entity\User;
use Fagathe\MonSite\Model\UserModel;
use Fagathe\Framework\Http\Session;
use Fagathe\Framework\Logger\Logger;
use Fagathe\Framework\Security\Guard\CustomAuthenticationMessage;
use Fagathe\Framework\Token\Token;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class Auth
{

    private const AUTHENTICATION_ERROR = 'authentication_error';
    private const MISSING_DATA_MESSAGE = 'Le nom d\'utilisateur et le mot de passe sont obkigatoires.';
    private const INVALID_CREDENTIALS = 'Identifiants incorrects.';
    private const LOGIN_PAGE = '/login';
    private const DEFAULT_REDIRECT_ROUTE = '/';
    public const AUTH_TOKEN_KEY = 'X_AUTH_TOKEN';
    public const REMEMBER_ME_KEY = 'REMEMBER_ME';
    private UserModel $userModel;
    private Session $session;
    private Request $request;
    private Logger $logger;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->session = new Session();
        $this->userModel = new UserModel();
        $this->logger = new Logger('auth/login.log');
    }

    private function supports(): bool
    {
        return $this->request->isMethod('POST') && $this->request->getPathInfo() === self::LOGIN_PAGE;
    }

    private function persist(string $token): void
    {
        $token = base64_decode($token);
        // Store user in session
        $this->session->set(self::AUTH_TOKEN_KEY, $token);
        return;
    }

    /**
     * @return void
     */
    public function login(): void
    {
        if (!$this->supports()) {
            $this->errorMessage('BAD REQUEST !');
            $this->logger->info(__METHOD__ . ' ::: Malicious Bad Request been sent with data : ' . json_encode($this->request->request->all()) . ', url : ' . $this->request->getPathInfo());
            return;
        }

        $username = $this->request->request->get('username');
        $password = $this->request->request->get('password');

        if (empty($username) || empty($password)) {
            $this->errorMessage(self::MISSING_DATA_MESSAGE);
            $this->logger->error(__METHOD__ . ' ::: Missing data for user ' . json_encode(compact('username')) . ' to log in.');
            return;
        }

        $user = $this->userModel->findOneByUsername($username);
        $this->logger->info(__METHOD__ . ' ::: User ' . $username . ' trying to log in.');
        if ($user instanceof User) {
            // Check if password is correct
            if (PasswordHasher::verify($password, $user->getPassword())) {
                // Generate token if not exists
                if ($user->getToken() !== null) {
                    $token = $user->getToken();
                } else {
                    $token = Token::generate(length: 60, unique: true);
                    $this->userModel->update(compact('token'), ['id' => $user->getId()]);
                }

                // Persist user in session
                $this->persist($token);

                // Add Remember me to save user credentials
                $this->remember_me($user);

                // Message authentication for user
                new CustomAuthenticationMessage('Bonjour ' . $username . '✋', 'success');
                $this->logger->info(__METHOD__ . ' ::: User ' . $username . ' logged in successfully.');

                // Redirect to home page
                (new RedirectResponse(self::DEFAULT_REDIRECT_ROUTE))->send();
                exit;
            } else {
                $this->logger->error(__METHOD__ . ' ::: User ' . $username . ' failed to log in.');
                $this->errorMessage(self::INVALID_CREDENTIALS);
                exit;
            }
        } else {
            $this->logger->error(__METHOD__ . ' ::: unknown User ' . $username . ' to log in.');
            $this->errorMessage(self::INVALID_CREDENTIALS);
            exit;
        }

    }

    /**
     * @param string $message
     * 
     * @return void
     */
    private function errorMessage(string $message): void
    {
        $this->session->addFlash([
            'message' => $message,
            'type' => self::AUTHENTICATION_ERROR,
        ], 'authentication_error');
    }

    /**
     * @param User $user
     * 
     * @return void
     */
    public function remember_me(User $user): void
    {
        $remember_me = $this->request->request->get('remember_me');
        if ($remember_me === 'on') {
            $rememberMeData = json_encode([
                'password' => $user->getPassword(),
                'username' => $user->getUsername(),
                'expired_at' => (new \DateTimeImmutable())->modify('+2 month')->format('Y-m-d H:i:s')
            ]);
            setcookie(self::REMEMBER_ME_KEY, base64_encode($rememberMeData), expires_or_options: time() + 60 * 60 * 24 * 60);

            $this->logger->info(sprintf('%s  :::  Persist User Credentials  %s  remembered.', __METHOD__, $user->getUsername()));
        }
    }

    public function loginRememberMe(): void
    {
        $rememberMe = $this->getRememberMe();
        if ($rememberMe !== null) {
            $user = $this->userModel->findOneByUsername($rememberMe['username']);
            if ($user instanceof User) {
                if ($rememberMe['password'] === $user->getPassword()) {
                    $this->persist($user->getToken());
                    $this->logger->info(sprintf('%s  :::  User %s remembered.', __METHOD__, $user->getUsername()));
                    (new RedirectResponse(self::DEFAULT_REDIRECT_ROUTE))->send();
                    exit();
                }
                $this->logger->info(sprintf('%s  :::  User %s remembered had wrong credentials.', __METHOD__, $user->getUsername()));
                exit();
            }
            $this->logger->info(sprintf('%s  :::  REMEMBER_ME user does not exist in database.', __METHOD__));
            exit();
        }
    }

    /**
     * @return array|null
     */
    public function getRememberMe(): ?array
    {
        $rememberMe = $this->request->cookies->get(self::REMEMBER_ME_KEY);
        if ($rememberMe !== null) {

            return json_decode(base64_decode($rememberMe), true);
        }
        return null;
    }

    /**
     * @return void
     */
    public static function logout(): void
    {
        $session = new Session();
        $session->remove(self::REMEMBER_ME_KEY);
        (new RedirectResponse(self::DEFAULT_REDIRECT_ROUTE))->send();
        exit();
    }
}
