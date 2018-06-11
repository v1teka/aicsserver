<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inventory extends Model
{
    protected $fillable = ['number', 'type_id', 'classroom_id'];
}
