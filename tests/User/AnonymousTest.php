<?php

namespace App\Tests\User;

use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AnonymousTest extends AbstractWebTestCase
{
    public function testInvalidEmailLogin(): void
    {
        $response = $this->post('/login', ['email' => 'noop', 'password' => 'noop']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testInvalidPasswordLogin(): void
    {
        $response = $this->post('/login', ['email' => 'admin@example.com', 'password' => 'noop']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testBlockedUserLogin(): void
    {
        $response = $this->post('/login', ['email' => 'user1@example.com', 'password' => 'test123']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAnonymousUserMe(): void
    {
        $response = $this->get('/user/me');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

//    public function testAnonymousUserGet(): void
//    {
//        $response = $this->get('/user/1');
//
//        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
//    }
//
//    public function testAnonymousUserEdit(): void
//    {
//        $response = $this->post('/user/1/edit');
//
//        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
//    }
}
