<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    protected $fillable = ['start_time', 'end_time', 'client_id', 'prestataire_id', 'user_id', 'status'];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

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
     * Retourne le statut textuel (à partir de la relation ou calculé sur start_time)
     */
    public function getStatusTextAttribute()
    {
        if ($this->relationLoaded('status')) {
            $rel = $this->getRelation('status');
            if ($rel instanceof Status) {
                return $rel->name;
            }
        }
        if ($this->status) {
            $status = Status::find($this->status);
            if ($status) {
                return $status->name;
            }
        }
        if ($this->start_time instanceof Carbon) {
            return $this->start_time->lte(Carbon::now()) ? 'passé' : 'à venir';
        }
        return '';
    }
}