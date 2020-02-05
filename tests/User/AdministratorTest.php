<?php

namespace App\Tests\User;

use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdministratorTest extends AbstractWebTestCase
{
    public function testAdminCanLogin(): array
    {
        $response = $this->post('/login', ['email' => 'admin@example.com', 'password' => 'test123']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        return [
            'cookies' => $response->headers->getCookies(),
        ];
    }

    /**
     * @depends testAdminCanLogin
     */
    public function testAdminCanGetMe(array $data): void
    {
        $this->setCookies($data['cookies']);
        $response = $this->get('/users/me');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @depends testAdminCanLogin
     */
    public function testAdminCanGetOtherUser(array $data): array
    {
        $this->setCookies($data['cookies']);
        $response = $this->get('/api/users/2');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data['user'] = $this->toArray($response);

        return $data;
    }

    /**
     * @depends testAdminCanGetOtherUser
     */
    public function testAdminCanEditOtherUser(array $data): void
    {
        $this->setCookies($data['cookies']);

        $user = $data['user'];

        $response = $this->post('/api/users/2/edit', [], json_encode($user, JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @depends testAdminCanLogin
     */
    public function testAdminCanCreateUser(array $data): array
    {
        $this->setCookies($data['cookies']);

        $user = [
            'email' => 'user10@example.com'
        ];

        $response = $this->post('/api/users', [], json_encode($user, JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data['user'] = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        return $data;
    }

    /**
     * @depends testAdminCanCreateUser
     */
    public function testAdminCanDeleteUser(array $data): void
    {
        $userId = $data['user']['id'];

        $this->setCookies($data['cookies']);
        $response = $this->delete("/user/$userId/delete");

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

}
