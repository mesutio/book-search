<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class InvalidParameterException extends BadRequestException
{
    protected const CODE = 400;
}