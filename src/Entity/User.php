<?php

namespace Entity;

class User
{
    public readonly int $id;
    public readonly string $email;

    public function __construct(
        string $email,
        public readonly string $password
    ) {
        
    }
}
