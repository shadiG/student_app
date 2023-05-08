<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class ClassroomFilter extends ApiFilter
{
    protected $safeParams = [
        'name' => ['eq'],
    ];
}
