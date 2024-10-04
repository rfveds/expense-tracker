<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Entity\Category;
use DateTime;

readonly class TransactionData
{
    public function __construct(
        public string $description,
        public float $amount,
        public DateTime $date,
        public Category $category
    ) {
    }
}