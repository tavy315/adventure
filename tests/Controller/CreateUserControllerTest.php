<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateUserControllerTest extends WebTestCase
{
    public function testCreateUser(): void
    {
        $faker = Factory::create();
        $email = $faker->email();

        $client = self::createClient();
        $client->request(Request::METHOD_POST, '/create-user', [
            'email'    => $email,
            'name'     => $faker->name(),
            'password' => $faker->password(),
        ]);

        $em = self::getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        self::assertTrue($em->contains($user));
        self::assertEmailCount(1);
        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}
