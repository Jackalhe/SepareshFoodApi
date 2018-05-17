<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EndUserModel extends Model
{
    protected $table = 'EndUser';
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'password'
    ];
}
