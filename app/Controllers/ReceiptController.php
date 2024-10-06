<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\EntityManagerServiceInterface;
use App\Contracts\RequestValidatorFactoryInterface;
use App\Entity\Receipt;
use App\Entity\Transaction;
use App\RequestValidators\Receipt\UploadReceiptRequestValidator;
use App\ResponseFormatter;
use App\Services\FileService;
use App\Services\ReceiptService;
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
        private ResponseFormatter $responseFormatter,
        private FileService $fileService,
        private EntityManagerServiceInterface $entityManagerService
    ) {
    }

    public function store(Request $request, Response $response, Transaction $transaction): Response
    {
        /** @var UploadedFileInterface $file */
        $file = $this->requestValidatorFactory->make(UploadReceiptRequestValidator::class)->validate(
            $request->getUploadedFiles()
        )['receipt'];

        $randomFilename = $this->fileService->save($file);

        $receipt = $this->receiptService->create(
            $transaction,
            $file->getClientFilename(),
            $randomFilename,
            $file->getClientMediaType()
        );

        $this->entityManagerService->sync($receipt);

        return $response;
    }

    public function download(Response $response, Transaction $transaction, Receipt $receipt): Response
    {
        if ($receipt->getTransaction()->getId() !== $transaction->getId()) {
            return $response->withStatus(401);
        }

        return $this->responseFormatter->asFile(
            $response,
            $receipt->getFilename(),
            $receipt->getMediaType(),
            $this->filesystem->readStream('receipts/' . $receipt->getStorageFilename())
        );
    }

    public function delete(Response $response, Transaction $transaction, Receipt $receipt): Response
    {
        if ($receipt->getTransaction()->getId() !== $transaction->getId()) {
            return $response->withStatus(401);
        }

        $this->filesystem->delete('receipts/' . $receipt->getStorageFilename());

        $this->entityManagerService->delete($receipt, true);

        return $response;
    }
}