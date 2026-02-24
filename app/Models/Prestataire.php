<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestataire extends Model
{
    protected $table = 'prestataire';
    
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}