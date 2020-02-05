<?php

namespace App\Tests\User;

use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AnonymousTest extends AbstractWebTestCase
{
    public function testInvalidMailIsForbidden(): void
    {
        $response = $this->post('/login', ['email' => 'noop', 'password' => 'noop']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testInvalidPasswordIsForbidden(): void
    {
        $response = $this->post('/login', ['email' => 'admin@example.com', 'password' => 'noop']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testBlockedUserCantLogin(): void
    {
        $response = $this->post('/login', ['email' => 'user1@example.com', 'password' => 'test123']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousUserCantGetMe(): void
    {
        $response = $this->get('/users/me');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousUserCantGetOtherUser(): void
    {
        $response = $this->get('/api/users/1');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousUserCantEditOtherUser(): void
    {
        $response = $this->patch('/api/users/1');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousCantCreateUser(): void
    {
        $response = $this->post('/api/users');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousCantDeleteUser(): void
    {
        $response = $this->delete('/api/users/1');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousCantAccessUserCollection(): void
    {
        $response = $this->get('/api/users');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
