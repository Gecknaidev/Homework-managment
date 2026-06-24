<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'username',
        'fullname',
        'gender',
        'password',
        'phone',
        'address',
        'position',
    ];
}