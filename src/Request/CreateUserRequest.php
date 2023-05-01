<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateUserRequest
{
    public function __construct(
        #[NotBlank()]
        #[Length(null, 6, 150)]
        public readonly string $email,
        #[NotBlank()]
        #[Length(null, 6, 150)]
        public readonly string $name,
        #[NotBlank()]
        #[Length(null, 5)]
        public readonly string $password
    ) {
    }
}
