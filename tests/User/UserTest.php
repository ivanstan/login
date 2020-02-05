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
    public function testUserCantGetOtherUser(array $data): void
    {
        $this->setCookies($data['cookies']);
        $response = $this->get('/api/users/1');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @depends testUserLogin
     */
    public function testUserCanGetMe(array $data): array
    {
        $this->setCookies($data['cookies']);
        $response = $this->get('/users/me');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data['user'] = $this->toArray($response);

        return $data;
    }

    public function testUserCantEditOtherUser(): void
    {
        $response = $this->patch('/api/users/1');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @depends testUserCanGetMe
     */
    public function testUserCanEditSelf(array $data): void
    {
        $this->setCookies($data['cookies']);

        $user = $data['user'];

        $response = $this->patch("/api/users/{$user['id']}", [], json_encode($user, JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @depends testUserLogin
     */
    public function testUserCantCreateUser(array $data): void
    {
        $this->setCookies($data['cookies']);
        $response = $this->post('/api/users');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @depends testUserLogin
     */
    public function testUserCantDeleteUser(array $data): void
    {
        $this->setCookies($data['cookies']);
        $response = $this->delete('/api/users/1');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @depends testUserLogin
     */
    public function testUserCantAccessUserCollection(array $data): void
    {
        $this->setCookies($data['cookies']);
        $response = $this->get('/api/users');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
