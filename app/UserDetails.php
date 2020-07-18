<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $fillable = [
        'user_id', 'dob', 'gender', 'mobile', 'address'
    ];
}
