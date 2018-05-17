<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmployerModel extends Model
{
    protected $table = 'employer';
    protected $fillable = [
        'title',
        'Comment',
        'min_order',
        'deliver_district',
        'address_comment',
        'sat_start',
        'sun_start',
        'mon_start',
        'tue_start',
        'wed_start',
        'thu_start',
        'fri_start',

        'sat_end',
        'sun_end',
        'mon_end',
        'tue_end',
        'wed_end',
        'thu_end',
        'fri_end',

        'long',
        'lat'
    ];
}

