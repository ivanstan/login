<?php

namespace App\Tests\User;

use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends AbstractWebTestCase
{
    public function testUserLogin(): array
    {
        $response = $this->post('/login', ['email' => 'user2@example.com', 'password' => 'test123']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        return $response->headers->getCookies();
    }

    public function testAnonymousUserMe(): void
    {
        $response = $this->get('/me');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}