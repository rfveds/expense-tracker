<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

trait HasId
{
    #[Id]
    #[Column(options: ['unsigned' => true])]
    #[GeneratedValue]
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}