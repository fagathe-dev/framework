<?php 
namespace Fagathe\Framework\Security;

use Fagathe\Framework\Http\Session;
use Fagathe\Framework\Token\Token;

class CSRF {
    private $token;
    private $session;
    private const TOKEN_CSRF_NAME = 'csrf_token';
    private const TOKEN_CSRF_ERROR_MSG = 'Invalid CSRF token.';

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @return string|null
     */
    public function getToken():?string
    {
        return $this->session->get(self::TOKEN_CSRF_NAME);
    }

    /**
     * @return void
     */
    public function generate():void 
    {
        $this->token = Token::generate(length: 64, unique: true);
        return $this->session->set(self::TOKEN_CSRF_NAME, $this->token);
    }

    /**
     * @param string $token
     * 
     * @return bool
     */
    public function checkToken(string $token):bool
    {
        return $this->getToken() === $token;
    }
}