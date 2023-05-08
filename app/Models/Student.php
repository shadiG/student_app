<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'first_name',
        'last_name',
        'email',
        'gender',
        'date_of_birth'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
