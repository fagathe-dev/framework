<?php
namespace Fagathe\Framework\Security\Guard;

use Fagathe\Framework\Http\Session;

final class CustomAuthenticationMessage
{

    private Session $session;
    private const DEFAULT_MESSAGE_TYPE = 'info';

    public function __construct(string $message = '', string $type = 'info')
    {
        $this->session = new Session();
        if ($message !== '' && !$this->session->has('authenficationMessage')) {
            $this->session->set('authenficationMessage', [
                'message' => $message,
                'type' => $type
            ]);
        }
    }

    public function getMessage(): string
    {
        return $this->session->get('authenficationMessage')['message'] ?? '';
    }

    public function getType(): string
    {
        return $this->session->get('authenficationMessage')['type'] ?? static::DEFAULT_MESSAGE_TYPE;
    }

}
