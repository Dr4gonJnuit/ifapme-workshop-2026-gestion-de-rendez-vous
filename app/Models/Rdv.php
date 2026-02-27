<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Client;
use App\Models\Prestataire;
use App\Models\Utilisateur;
use App\Models\Status;

class Rdv extends Model
{
    protected $table = 'rdv';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // mass assignment and casting for the rdv attributes
    protected $fillable = ['date', 'client_id', 'prestataire_id', 'user_id', 'status'];
    protected $casts = ['date' => 'datetime'];

    public function client() {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function prestataire() {
        return $this->belongsTo(Prestataire::class, 'prestataire_id', 'id');
    }

    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class, 'user_id', 'id');
    }

    public function status() {
        return $this->belongsTo(Status::class, 'status', 'id');
    }

    // generate UUID automatically when creating
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
     * Return a human-readable status (name field or derived from date).
     */
    public function getStatusTextAttribute()
    {
        // prefer the stored/related status if available
        if ($this->relationLoaded('status')) {
            $rel = $this->getRelation('status');
            if ($rel instanceof Status) {
                return $rel->name;
            }
        }
        if ($this->status) {
            // if relation not loaded, fall back to name lookup from the id
            $status = Status::find($this->status);
            if ($status) {
                return $status->name;
            }
        }

        // as a last resort, compute from the date
        if ($this->date instanceof Carbon) {
            return $this->date->lte(Carbon::now()) ? 'passé' : 'à venir';
        }

        return '';
    }
}