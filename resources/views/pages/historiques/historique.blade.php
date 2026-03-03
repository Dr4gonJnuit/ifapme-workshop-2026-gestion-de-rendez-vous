@extends('layouts.app')

@section('content')

<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-6">Historique des rendez-vous</h1>

    @if($rdvs->isEmpty())
        <p class="text-gray-500">Aucun rendez-vous passé.</p>
    @else
        <table class="min-w-full border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="p-3 text-left">Client</th>
                    <th class="p-3 text-left">Prestataire</th>
                    <th class="p-3 text-left">Début</th>
                    <th class="p-3 text-left">Fin</th>
                    <th class="p-3 text-left">Statut</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($rdvs as $rdv)
                    <tr class="border-t dark:border-gray-700">
                        <td class="p-3">
                            {{ $rdv->client->firstname ?? 'Non défini' }}
                            {{ $rdv->client->lastname ?? '' }}
                        </td>

                        <td class="p-3">
                            {{ $rdv->prestataire->firstname ?? 'Non défini' }}
                            {{ $rdv->prestataire->lastname ?? '' }}
                            ({{ $rdv->prestataire->profession ?? '' }})
                        </td>

                        <td class="p-3">
                            {{ $rdv->start_time->format('d/m/Y H:i') }}
                        </td>

                        <td class="p-3">
                            {{ $rdv->end_time->format('d/m/Y H:i') }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $rdvs->links() }}
        </div>
    @endif

</div>

@endsection