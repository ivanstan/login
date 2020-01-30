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

        return [
            'cookies' => $response->headers->getCookies(),
        ];
    }

    /**
     * @depends testUserLogin
     */
    public function testAnonymousUserMe(array $data): void
    {
        $this->setCookies($data['cookies']);
        $response = $this->get('/user/1');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}