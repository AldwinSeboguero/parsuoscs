<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $table='admin_setting';
    protected $fillable=['name','value'];
}
