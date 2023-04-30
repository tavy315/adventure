<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GalactusService
{
    public function __construct(private readonly HttpClientInterface $galactusClient)
    {
    }

    public function getKid(string $email): string
    {
        $response = $this->galactusClient->request(Request::METHOD_POST, 'kid', [
            'body' => [
                'email' => $email,
            ],
        ]);

        $content = $response->toArray();

        return $content['kid'] ?? '';
    }
}
