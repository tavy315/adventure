<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Service\GalactusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly GalactusService $galactusService,
        private readonly MailerInterface $mailer,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[Route('/create-user', name: 'app_create_user', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user);
        $form->submit($request->request->all());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json(null, Response::HTTP_BAD_REQUEST);
        }

        $user->setKid($this->galactusService->getKid($user->getEmail()));
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));

        $this->userRepository->save($user, true);

        $email = (new Email())
            ->to(new Address($user->getEmail()))
            ->subject('Welcome')
            ->html('<p>emails.welcome</p>');

        $this->mailer->send($email);

        return $this->json($user, Response::HTTP_CREATED);
    }
}
