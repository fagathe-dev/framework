<?php
namespace Fagathe\Framework\Security;

interface UserInterface
{

    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getUsername(): ?string;

    /**
     * @return string|null
     */
    public function getPassword(): ?string;

    /**
     * @param string $password
     * 
     * @return self
     */
    public function setPassword(string $password): self;

    /**
     * @return string|null
     */
    public function getRole(): ?string;

    /**
     * @return self
     */
    public function setRole(?string $role = null): self;

    /**
     * @return string|null
     */
    public function getToken(): ?string;

    /**
     * @param string $token
     * 
     * @return self
     */
    public function setToken(?string $token): self;

}