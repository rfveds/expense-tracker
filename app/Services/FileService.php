<?php

namespace App\Services;

use League\Flysystem\Filesystem;
use Psr\Http\Message\UploadedFileInterface;

readonly class FileService
{
    public function __construct(
        private Filesystem $filesystem
    ) {
    }

    public function save(UploadedFileInterface $file): string
    {
        $randomFilename = bin2hex(random_bytes(25));

        $this->filesystem->write('receipts/' . $randomFilename, $file->getStream()->getContents());

        return $randomFilename;
    }
}