<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rdv extends Model
{
    protected $table = 'rdv';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

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
}