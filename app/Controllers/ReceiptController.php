<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use App\RequestValidators\Receipt\UploadReceiptRequestValidator;
use App\ResponseFormatter;
use App\Services\FileService;
use App\Services\ReceiptService;
use App\Services\TransactionService;
use League\Flysystem\Filesystem;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;

readonly class ReceiptController
{
    public function __construct(
        private Filesystem $filesystem,
        private RequestValidatorFactoryInterface $requestValidatorFactory,
        private ReceiptService $receiptService,
        private TransactionService $transactionService,
        private ResponseFormatter $responseFormatter,
        private FileService $fileService
    ) {
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        /** @var UploadedFileInterface $file */
        $file = $this->requestValidatorFactory->make(UploadReceiptRequestValidator::class)->validate(
            $request->getUploadedFiles()
        )['receipt'];

        $id = (int)$args['id'];

        if (!$id || !($transaction = $this->transactionService->getById($id))) {
            return $response->withStatus(404);
        }

        $randomFilename = $this->fileService->save($file);

        $this->receiptService->create(
            $transaction,
            $file->getClientFilename(),
            $randomFilename,
            $file->getClientMediaType()
        );

        return $response;
    }

    public function download(Request $request, Response $response, array $args): Response
    {
        $transactionId = (int)$args['transactionId'];
        $receiptId     = (int)$args['id'];

        if (!$transactionId || !$this->transactionService->getById($transactionId)) {
            return $response->withStatus(404);
        }

        if (!$receiptId || !($receipt = $this->receiptService->getById($receiptId))) {
            return $response->withStatus(404);
        }

        if ($receipt->getTransaction()->getId() !== $transactionId) {
            return $response->withStatus(401);
        }

        return $this->responseFormatter->asFile(
            $response,
            $receipt->getFilename(),
            $receipt->getMediaType(),
            $this->filesystem->readStream('receipts/' . $receipt->getStorageFilename())
        );
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        // TODO

        return $response;
    }
}