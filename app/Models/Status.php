<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // table name is singular in database
    protected $table = 'status';

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'name'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * Utility for obtaining a status record by name, creating if missing.
     */
    public static function findOrCreate(string $name)
    {
        return static::firstOrCreate(['name' => $name]);
    }
}
