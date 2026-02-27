<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Prestataire extends Model
{
    protected $table = 'prestataire';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['firstname', 'lastname', 'profession', 'email', 'phone'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public $timestamps = false; // car ta table n'a pas created_at / updated_at
}