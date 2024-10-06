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
use Slim\Views\Twig;
use Twig\Environment;

readonly class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private AuthInterface $auth,
        private EntityManagerServiceInterface $entityManagerService,
        private Twig $twig
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($user = $this->auth->user()) {
            $this->twig->getEnvironment()->addGlobal('auth', ['id' => $user->getId(), 'name' => $user->getName()]);

            $this->entityManagerService->enableUserAuthFilter($user->getId());

            return $handler->handle($request->withAttribute('user', $user));
        }

        return $this->responseFactory->createResponse(302)->withHeader('Location', '/login');
    }
}