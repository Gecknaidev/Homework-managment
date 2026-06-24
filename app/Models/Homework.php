<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    protected $table = 'homeworks';

    protected $fillable = [
        'title',
        'description',
        'attachment',
        'role',
        'submitted_by',
        'student_name',
    ];
}