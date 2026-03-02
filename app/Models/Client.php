<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    protected $table = 'client';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    use SoftDeletes;

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'firstname',
        'lastname',
        'email',
        'phone',
    ];
}
