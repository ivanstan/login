<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AbstractWebTestCase extends WebTestCase
{
    protected static AbstractBrowser $client;

    protected function setUp(): void
    {
        self::$client = static::createClient();
        parent::setUp();
    }

    protected function get(string $url, array $params = []): Response
    {
        self::$client->request('GET', $this->buildUrl($url, $params));

        return self::$client->getResponse();
    }

    protected function post(string $url, array $params = [], $content = null): Response
    {
        self::$client->request('POST', $url, $params, [], [], $content);

        return self::$client->getResponse();
    }

    protected function patch(string $url, array $params = [], $content = null): Response
    {
        self::$client->request('PATCH', $url, $params, [], [], $content);

        return self::$client->getResponse();
    }

    protected function delete(string $url, array $params = [], $content = null): Response
    {
        self::$client->request('DELETE', $url, $params, [], [], $content);

        return self::$client->getResponse();
    }


    protected function toArray(Response $response): array
    {
        return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    protected function setCookies(array $cookies): void
    {
        foreach ($cookies as $cookie) {
            self::$client->getCookieJar()->set(new Cookie($cookie->getName(), $cookie->getValue()));
        }
    }

    private function buildUrl(string $url, array $params = []): string
    {
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }
}
