<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Rdv;
use App\Models\Status;
use Illuminate\Support\Carbon;

class RdvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date',
                'after:now',
                function ($attribute, $value, $fail) {
                    $this->validateNoConflictingAppointment($value, $fail);
                },
            ],
            'client_id' => 'required|exists:client,id',
            'prestataire_id' => 'required|exists:prestataire,id',
        ];
    }

    protected function validateNoConflictingAppointment($dateValue, $fail)
    {
        $date = Carbon::parse($dateValue);
        $aVenirStatusId = Status::findOrCreate('à venir')->id;

        // window: 15 minutes before and after
        $start = $date->clone()->subMinutes(15);
        $end = $date->clone()->addMinutes(15);

        // exclude current rdv if updating
        $rdvId = $this->route('rdv')?->id;

        $conflict = Rdv::where('status', $aVenirStatusId)
            ->whereBetween('date', [$start, $end])
            ->when($rdvId, fn ($q) => $q->where('id', '!=', $rdvId))
            ->exists();

        if ($conflict) {
            $fail('Un rendez-vous existe déjà dans cette plage horaire (±15 minutes).');
        }
    }
}
