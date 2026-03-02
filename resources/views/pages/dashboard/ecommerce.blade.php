@extends('layouts.app')

@php
use Illuminate\Support\Carbon;
@endphp

@section('content')
<div class="p-6 space-y-6">

    <h1 class="text-2xl font-bold">Bienvenue, {{ Auth::user()->username ?? 'Utilisateur' }} !</h1>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Rendez-vous à venir cette semaine</h2>
        @php
            $today = Carbon::today();
            $endOfWeek = Carbon::today()->endOfWeek();
            $weeklyRdv = \App\Models\Rdv::with(['client', 'prestataire', 'status'])
                            ->where('user_id', Auth::id())
                            ->whereBetween('date', [$today, $endOfWeek])
                            ->orderBy('date', 'asc')
                            ->get();
        @endphp

        @if($weeklyRdv->isEmpty())
            <p class="text-gray-500">Aucun rendez-vous prévu cette semaine.</p>
        @else
            <ul class="space-y-3">
                @foreach($weeklyRdv as $rdv)
                    <li class="p-3 border rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between">
                            <span class="font-medium">
                                {{ $rdv->date->format('d/m/Y H:i') }}
                            </span>
                            <span class="text-theme-sm">
                                Client : {{ $rdv->client->firstname ?? 'Non défini' }} {{ $rdv->client->lastname ?? '' }}
                            </span>
                        </div>
                        <div class="mt-1 text-gray-500 text-sm">
                            Prestataire : 
                            {{ $rdv->prestataire->firstname ?? 'Non défini' }} {{ $rdv->prestataire->lastname ?? '' }} 
                            ({{ $rdv->prestataire->profession ?? 'Non défini' }}) 
                            | Statut : {{ $rdv->status_text }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Rendez-vous à venir ce mois</h2>
        @php
            $endOfMonth = Carbon::today()->endOfMonth();
            $monthlyRdv = \App\Models\Rdv::with(['client', 'prestataire', 'status'])
                            ->where('user_id', Auth::id())
                            ->whereBetween('date', [$today, $endOfMonth])
                            ->orderBy('date', 'asc')
                            ->get();
        @endphp

        @if($monthlyRdv->isEmpty())
            <p class="text-gray-500">Aucun rendez-vous prévu ce mois.</p>
        @else
            <ul class="space-y-3">
                @foreach($monthlyRdv as $rdv)
                    <li class="p-3 border rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between">
                            <span class="font-medium">
                                {{ $rdv->date->format('d/m/Y H:i') }}
                            </span>
                            <span class="text-theme-sm">
                                Client : {{ $rdv->client->firstname ?? 'Non défini' }} {{ $rdv->client->lastname ?? '' }}
                            </span>
                        </div>
                        <div class="mt-1 text-gray-500 text-sm">
                            Prestataire : 
                            {{ $rdv->prestataire->firstname ?? 'Non défini' }} {{ $rdv->prestataire->lastname ?? '' }} 
                            ({{ $rdv->prestataire->profession ?? 'Non défini' }}) 
                            | Statut : {{ $rdv->status_text }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</div>
@endsection