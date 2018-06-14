<?php

namespace App\Service\Processor;

use App\Service\Contract\Processor;

class ErrorProcessor implements Processor
{
    public function getResponse()
    {
        return 'An error occurred';
    }
}