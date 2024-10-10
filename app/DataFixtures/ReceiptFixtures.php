<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Receipt;
use App\Services\FileService;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Slim\Psr7\UploadedFile;

class ReceiptFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    private ?FileService $fileService = null;

    public function loadData(): void
    {
        $adapter           = new LocalFilesystemAdapter(STORAGE_PATH);
        $filesystem        = new Filesystem($adapter);
        $this->fileService = new FileService($filesystem);

        $filePath = PAYLOAD_PATH . '/fixtures/receipt/generic_receipt.pdf';

        $this->createMany(20, 'receipts', function () use ($filePath) {
            [$randomFilename, $mimeType] = $this->getFileInfo($filePath);

            $receipt = new Receipt();
            $receipt->setFilename($this->faker->word);
            $receipt->setTransaction($this->getRandomReference('transactions'));
            $receipt->setStorageFilename($randomFilename);
            $receipt->setMediaType($mimeType);

            return $receipt;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [TransactionFixtures::class];
    }

    private function getFileInfo($filePath): array
    {
        $file           = new UploadedFile($filePath);
        $tmpFilePath    = $file->getStream()->getMetadata('uri');
        $detector       = new FinfoMimeTypeDetector();
        $mimeType       = $detector->detectMimeTypeFromFile($tmpFilePath);
        $randomFilename = $this->fileService->save($file);

        return [$randomFilename, $mimeType];
    }
}
