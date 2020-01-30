<?php

namespace App\Tests\User;

use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdministratorTest extends AbstractWebTestCase
{
    public function testAdminUserLogin(): array
    {
        $response = $this->post('/login', ['email' => 'admin@example.com', 'password' => 'test123']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        return [
            'cookies' => $response->headers->getCookies(),
        ];
    }

    /**
     * @depends testAdminUserLogin
     */
    public function testMe(array $data): void
    {
        $this->setCookies($data['cookies']);
        $response = $this->get('/user/me');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @depends testAdminUserLogin
     */
    public function testGetUser(array $data): array
    {
        $this->setCookies($data['cookies']);
        $response = $this->get('/user/2');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        return [
            'cookies' => $data['cookies'],
            'user' => $this->toArray($response),
        ];
    }

    /**
     * @depends testGetUser
     */
    public function testEditUser(array $data): void
    {
        $this->setCookies($data['cookies']);

        $user = $data['user'];

        $response = $this->post('/user/2/edit', [], json_encode($user, JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
