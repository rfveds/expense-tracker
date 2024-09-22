<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\SessionException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StartSessionMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new  SessionException('Session already started');
        }

        if (headers_sent()) {
            throw new  SessionException('Headers already sent');
        }

        session_set_cookie_params([
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();

        $response = $handler->handle($request);

        // session_write_close() is called to close the session and release the lock on the session file.
        session_write_close();

        return $response;
    }
}