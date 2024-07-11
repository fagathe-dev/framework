<?php
namespace Fagathe\Framework\Http;

class Session
{
    public function __construct()
    {
        $this->init();
    }

    private function init(): ?bool
    {
        if (session_status() !== PHP_SESSION_DISABLED && session_status() !== PHP_SESSION_NONE) {
            return session_start();
        }
        return null;
    }

    public function all(): ?array
    {
        return $_SESSION;
    }

    /**
     * @param string $key
     * 
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * @param string $key
     * @param string|float|array|object|boolean $value
     * 
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * 
     * @return string|float|array|object|boolean
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * @param string $key
     * 
     * @return void
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * @return void
     */
    public function destroy(): void
    {
        session_destroy();
    }

    /**
     * @return array
     */
    public function getFlashBag(): array
    {
        $flashes = $_SESSION['flashes'] ?? [];
        unset($_SESSION['flashes']);
        return $flashes;
    }

    /**
     * @param string|array $value
     * @param string $key
     * 
     * @return array
     */
    public function addFlash(string|array $value, string $key = 'info'): void
    {
        $_SESSION['flashes'] = [$key => $value];
    }
}