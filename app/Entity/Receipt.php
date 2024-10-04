<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\HasId;
use App\Traits\HasTimeStamps;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'receipts')]
class Receipt
{
    use HasId;
    use HasTimeStamps;

    #[Column(name: 'file_name')]
    private string $fileName;

    #[ManyToOne(inversedBy: 'receipts')]
    private Transaction $transaction;

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): Receipt
    {
        $this->fileName = $fileName;
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
}