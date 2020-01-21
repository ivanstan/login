<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;

class SecurityTest extends AbstractWebTestCase
{
    public function testInvalidEmailLogin(): void
    {
        $response = $this->post('/login', [
            'email' => 'noop',
            'password' => 'noop'
        ]);

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testInvalidPasswordLogin(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'noop'
        ]);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAdminUserLogin(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'test123'
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testBlockedUserLogin(): void
    {
        $response = $this->post('/login', [
            'email' => 'user1@example.com',
            'password' => 'test123'
        ]);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousUserMe(): void
    {
        $response = $this->get('/me');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}