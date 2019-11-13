<?php

namespace App\Router;

use App\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

trait RouterAction
{
    private $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function redirect(string $path, array $params = []): ResponseInterface
    {
        $redirectUri = $this->router->generateUri($path, $params);
        return (new Response())
            ->withStatus(301)
            ->withHeader('Location', $redirectUri);
    }
}
