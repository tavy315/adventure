<?php

declare(strict_types=1);

namespace App\Tests\Mock;

use Faker\Factory;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AbstractApiMock extends MockHttpClient
{
    public function __construct()
    {
        $callback = $this->handleRequests(...);

        parent::__construct($callback);
    }

    private function handleRequests(string $method, string $url): MockResponse
    {
        if ($method === Request::METHOD_POST && str_ends_with($url, '/kid')) {
            $faker = Factory::create();

            $kid = (string)$faker->numberBetween(1_000_000, 9_999_999);

            return new MockResponse(json_encode(['kid' => $kid]), ['http_code' => Response::HTTP_OK]);
        }

        throw new \UnexpectedValueException("Mock not implemented: $method/$url");
    }
}
