<?php

interface UserInterface
{

    public function getId();

    public function getUsername();

    public function getPassword() :string;

    public function setPassword(string $password);

    public function getRoles():array;

}