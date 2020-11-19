<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangePassword extends Model
{
    protected $table='change_passwords';
    protected $fillable=['user_id'];
}
