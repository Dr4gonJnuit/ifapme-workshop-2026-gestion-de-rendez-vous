<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RdvRequest extends FormRequest
{
    public function authorize()
    {
        return true; // À adapter selon votre système d'authentification
    }

    public function rules()
    {
        return [
            'start_time'      => 'required|date',
            'end_time'        => 'required|date|after:start_time',
            'client_id'       => 'required|exists:client,id',
            'prestataire_id'  => 'required|exists:prestataire,id',
        ];
    }

    public function messages()
    {
        return [
            'end_time.after' => 'La date de fin doit être postérieure à la date de début.',
        ];
    }
}