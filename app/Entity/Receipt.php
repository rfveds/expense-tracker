<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\HasId;
use App\Entity\Traits\HasTimeStamps;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'receipts')]
#[HasLifecycleCallbacks]
class Receipt
{
    use HasId;
    use HasTimeStamps;

    #[Column(name: 'filename')]
    private string $filename;

    #[ManyToOne(inversedBy: 'receipts')]
    private Transaction $transaction;

    #[Column(name: 'storage_filename')]
    private string $storageFilename;

    #[Column(name: 'media_type')]
    private string $mediaType;

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): Receipt
    {
        $this->filename = $filename;
        return $this;
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(Transaction $transaction): Receipt
    {
        $transaction->addReceipt($this);
        $this->transaction = $transaction;
        return $this;
    }

    public function getStorageFilename(): string
    {
        return $this->storageFilename;
    }

    public function setStorageFilename(string $storageFilename): Receipt
    {
        $this->storageFilename = $storageFilename;

        return $this;
    }

    public function getMediaType(): string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): Receipt
    {
        $this->mediaType = $mediaType;

        return $this;
    }
}