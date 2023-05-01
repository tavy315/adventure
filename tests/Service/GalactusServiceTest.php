<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\GalactusService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GalactusServiceTest extends TestCase
{
    private const KID = 'mocked kid';
    private const EMAIL = 'test@email.com';

    /**
     * @dataProvider provideKid
     */
    public function testGetKid(
        string $expectedKid,
        ResponseInterface $response
    ): void {
        $galactusClient = new MockHttpClient([$response]);
        $galactusService = new GalactusService($galactusClient);

        $kid = $galactusService->getKid(self::EMAIL);

        $this->assertSame($expectedKid, $kid);
    }

    public static function provideKid(): iterable
    {
        $response = new MockResponse(sprintf('{"kid": "%s"}', self::KID));
        yield 'good case' => [self::KID, $response];

        $response = new MockResponse('{}');
        yield 'invalid case' => ['', $response];
    }
}
