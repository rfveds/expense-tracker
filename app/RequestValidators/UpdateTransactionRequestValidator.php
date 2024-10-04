<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\Exception\ValidationException;
use Valitron\Validator;

class UpdateTransactionRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', 'description');
        $v->rule('lengthMax', 'description', 255);

//        $v->rule('required', 'amount');
//        $v->rule('numeric', 'amount');
//
//        $v->rule('required', 'category_id');
//
//        $v->rule('required', 'date');

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}