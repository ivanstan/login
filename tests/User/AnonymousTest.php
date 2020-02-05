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
        $response = $this->get('/user/me');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousUserCantGetOtherUser(): void
    {
        $response = $this->get('/user/1');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousUserCantEditOtherUser(): void
    {
        $response = $this->post('/user/1/edit');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousCantCreateUser(): void
    {
        $response = $this->post('/user/new');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousCantDeleteUser(): void
    {
        $response = $this->delete('/user/1/delete');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousCantAccessUserCollection(): void
    {
        $response = $this->delete('/users');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
