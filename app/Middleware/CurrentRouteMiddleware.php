<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\AuthInterface;
use App\Contracts\EntityManagerServiceInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Twig\Environment;

readonly class CurrentRouteMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Twig $twig
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->twig->getEnvironment()->addGlobal(
            'current_route',
            RouteContext::fromRequest($request)->getRoute()->getName()
        );

        return $handler->handle($request);
    }
}