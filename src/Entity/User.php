<?php

namespace App\Entity;

use DateTimeImmutable;
use Fagathe\Framework\Database\AbstractEntity;

class User extends AbstractEntity
{

    private $firstname;
    private $lastname;
    private $username;
    private $email;
    private $password;
    private $confirmed;
    private $token;
    private $roles;
    private $created_at;
    private $updated_at;

    /**
     * Get the value of firstname
     */
    public function getFirstname(): ?string
    {
        return ucfirst($this->firstname);
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     */
    public function getLastname(): ?string
    {
        return ucfirst($this->lastname);
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of role
     */
    public function getRoles(): ?array
    {
        if (is_string($this->roles)) {
            $this->roles = json_decode($this->roles);
        }
        return $this->roles;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */
    public function setRoles(?string $roles): self
    {
        if ($roles !== null) {
            $this->roles = json_decode($roles);
        } else {
            $this->roles = null;
        }

        return $this;
    }

    /**
     * Get the value of confirmed
     */
    public function getConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * Set the value of confirmed
     *
     * @return  self
     */
    public function setConfirmed(?bool $confirmed): self
    {
        $this->confirmed = $confirmed ?? false;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreatedAt(?string $created_at): self
    {
        if ($created_at !== null) {
            $this->created_at = new DateTimeImmutable($created_at, timezone: new \DateTimeZone(DEFAULT_DATE_TIMEZONE));
        } else {
            $this->created_at = null;
        }

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }


    /**
     * Get the value of updated_at
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */
    public function setUpdatedAt(?string $updated_at): self
    {
        if ($updated_at !== null) {
            $this->updated_at = new DateTimeImmutable($updated_at, timezone: new \DateTimeZone(DEFAULT_DATE_TIMEZONE));
        } else {
            $this->updated_at = null;
        }

        return $this;
    }
}