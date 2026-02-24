<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';
    
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}