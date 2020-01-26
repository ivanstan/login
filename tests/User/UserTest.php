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

    /**
     * @depends testUserLogin
     */
    public function testAnonymousUserMe(array $cookies): void
    {
        $this->setCookies($cookies);
        $response = $this->get('/user/1');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}