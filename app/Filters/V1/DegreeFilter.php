<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class DegreeFilter extends ApiFilter
{
    protected $safeParams = [
        'name' => ['eq'],
        'max_year' => ['eq', 'gt', 'gte', 'lt', 'lte']
    ];
}
