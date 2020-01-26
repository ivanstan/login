<?php

namespace App\Tests\User;

use App\Tests\AbstractWebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AdministratorTest extends AbstractWebTestCase
{
    public function testAdminUserLogin(): array
    {
        $response = $this->post('/login', ['email' => 'admin@example.com', 'password' => 'test123']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        return $response->headers->getCookies();
    }

    /**
     * @depends testAdminUserLogin
     */
    public function testMe(array $cookies): void
    {
        $this->setCookies($cookies);

        $response = $this->get('/me');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    private function setCookies(array $cookies): void
    {
        foreach ($cookies as $cookie) {
            self::$client->getCookieJar()->set(new Cookie($cookie->getName(), $cookie->getValue()));
        }
    }
}
