<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use App\RequestValidators\Transaction\TransactionImportRequestValidator;
use App\Services\ImportTransactionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;

readonly class TransactionImporterController
{
    public function __construct(
        private RequestValidatorFactoryInterface $requestValidatorFactory,
        private ImportTransactionService $importTransactionService,
    ) {
    }

    public function import(Request $request, Response $response): Response
    {
        /** @var UploadedFileInterface $file */
        $file = $this->requestValidatorFactory->make(TransactionImportRequestValidator::class)->validate(
            $request->getUploadedFiles()
        )['importFile'];

        $user = $request->getAttribute('user');

        $this->importTransactionService->importFromFile($file->getStream()->getMetadata('uri'), $user);

        return $response;
    }
}