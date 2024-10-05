<?php

namespace App\Enum;

enum StorageDriver: string
{
    case Local = 'local';
    case S3    = 's3';
}