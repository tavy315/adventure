<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\CreateUserRequest;
use App\Service\GalactusService;
use App\Service\MailerSender;
use App\Service\UserCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly GalactusService $galactusService,
        private readonly MailerSender $mailerSender,
        private readonly UserCreator $userCreator,
        private readonly ValidatorInterface $validator
    ) {
    }

    #[Route('/create-user', name: 'app_create_user', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] CreateUserRequest $request
    ): JsonResponse {
        $this->validator->validate($request);

        $user = $this->userCreator->create($request, $this->galactusService->getKid($request->email));

        $this->mailerSender->send($user->getEmail());

        return $this->json($user, Response::HTTP_CREATED);
    }
}
