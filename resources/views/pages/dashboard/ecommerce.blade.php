@extends('layouts.app')

@php
use Illuminate\Support\Carbon;
@endphp

@section('content')
<div class="p-6 space-y-6">

    <h1 class="text-2xl font-bold">Bienvenue, {{ Auth::user()->username ?? 'Utilisateur' }} !</h1>

    <div class="flex gap-4 mb-6">
        <a href="{{ route('rdvs.index') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
           Rendez-vous
        </a>
        <a href="{{ route('clients.index') }}" 
           class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
          Clients
        </a>
        <a href="{{ route('prestataire.index') }}" 
           class="px-4 py-2 bg-purple-600 text-white rounded-lg shadow hover:bg-purple-700 transition">
           Prestataires
        </a>
    </div>
    
    {{-- Rendez-vous de la semaine (tous, même passés) --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Rendez-vous de la semaine</h2>
        @php
            $today = Carbon::today();
            $endOfWeek = Carbon::today()->endOfWeek();

            $weeklyRdv = \App\Models\Rdv::with(['client', 'prestataire', 'status'])
                            ->where('user_id', Auth::id())
                            ->whereBetween('start_time', [$today, $endOfWeek])
                            ->orderBy('start_time', 'asc')
                            ->get();
        @endphp

        @if($weeklyRdv->isEmpty())
            <p class="text-gray-500">Aucun rendez-vous cette semaine.</p>
        @else
            <ul class="space-y-3">
                @foreach($weeklyRdv as $rdv)
                    <li class="p-3 border rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between">
                            <span class="font-medium">
                                {{ $rdv->start_time->format('d/m/Y H:i') }} - {{ $rdv->end_time->format('H:i') }}
                                ({{ $rdv->start_time->diffInMinutes($rdv->end_time) }} min)
                            </span>
                            <span class="text-theme-sm">
                                Client : {{ $rdv->client->firstname ?? 'Non défini' }} {{ $rdv->client->lastname ?? '' }}
                            </span>
                        </div>
                        <div class="mt-1 text-gray-500 text-sm">
                            Prestataire : 
                            {{ $rdv->prestataire->firstname ?? 'Non défini' }} {{ $rdv->prestataire->lastname ?? '' }} 
                            ({{ $rdv->prestataire->profession ?? 'Non défini' }}) 
                            @if($rdv->start_time->isPast())
                                <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Passé</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Rendez-vous du mois (tous, même passés) --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Rendez-vous du mois</h2>
        @php
            $endOfMonth = Carbon::today()->endOfMonth();

            $monthlyRdv = \App\Models\Rdv::with(['client', 'prestataire', 'status'])
                            ->where('user_id', Auth::id())
                            ->whereBetween('start_time', [$today, $endOfMonth])
                            ->orderBy('start_time', 'asc')
                            ->get();
        @endphp

        @if($monthlyRdv->isEmpty())
            <p class="text-gray-500">Aucun rendez-vous ce mois.</p>
        @else
            <ul class="space-y-3">
                @foreach($monthlyRdv as $rdv)
                    <li class="p-3 border rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between">
                            <span class="font-medium">
                                {{ $rdv->start_time->format('d/m/Y H:i') }} - {{ $rdv->end_time->format('H:i') }}
                                ({{ $rdv->start_time->diffInMinutes($rdv->end_time) }} min)
                            </span>
                            <span class="text-theme-sm">
                                Client : {{ $rdv->client->firstname ?? 'Non défini' }} {{ $rdv->client->lastname ?? '' }}
                            </span>
                        </div>
                        <div class="mt-1 text-gray-500 text-sm">
                            Prestataire : 
                            {{ $rdv->prestataire->firstname ?? 'Non défini' }} {{ $rdv->prestataire->lastname ?? '' }} 
                            ({{ $rdv->prestataire->profession ?? 'Non défini' }}) 
                            @if($rdv->start_time->isPast())
                                <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Passé</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</div>
@endsection