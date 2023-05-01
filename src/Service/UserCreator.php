<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\CreateUserRequest;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCreator
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository
    ) {
    }

    public function create(CreateUserRequest $request, string $kid): User
    {
        $user = new User();
        $user->setEmail($request->email);
        $user->setName($request->name);
        $user->setKid($kid);
        $user->setPassword($this->passwordHasher->hashPassword($user, $request->password));

        $this->userRepository->save($user, true);

        return $user;
    }
}
