<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class StudentFilter extends ApiFilter
{
    protected $safeParams = [
        'first_name' => ['eq'],
        'last_name' => ['eq'],
        'email' => ['eq'],
        'gender' => ['eq'],
        'date_of_birth' => ['eq', 'gt', 'gte', 'lt', 'lte']
    ];
}
