<?php

declare(strict_types=1);

use App\Config;
use App\Exception\ValidationException;
use App\Middleware\AuthenticateMiddleware;
use App\Middleware\OldFormDataMiddleware;
use App\Middleware\StartSessionMiddleware;
use App\Middleware\ValidationErrorsMiddleware;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $container = $app->getContainer();
    $config = $container->get(Config::class);

    $app->add(AuthenticateMiddleware::class);
    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
    $app->add(ValidationException::class);
    $app->add(ValidationErrorsMiddleware::class);
    $app->add(OldFormDataMiddleware::class);
    $app->add(StartSessionMiddleware::class);
    $app->addErrorMiddleware(
        (bool)$config->get('display_error_details'),
        (bool)$config->get('log_errors'),
        (bool)$config->get('log_error_details')
    );
};
