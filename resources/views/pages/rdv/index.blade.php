@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gestion des rendez-vous</h1>
        <a href="{{ route('rdvs.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            + Nouveau rendez-vous
        </a>
    </div>

    {{-- Formulaire de filtres --}}
    <form method="GET" action="{{ route('rdvs.index') }}" class="mb-6 bg-white shadow-md rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Filtre prestataire --}}
            <div>
                <label for="prestataire_id" class="block text-sm font-medium text-gray-700 mb-1">Prestataire</label>
                <select name="prestataire_id" id="prestataire_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tous</option>
                    @foreach($prestataires as $prestataire)
                        <option value="{{ $prestataire->id }}" {{ request('prestataire_id') == $prestataire->id ? 'selected' : '' }}>
                            {{ $prestataire->firstname }} {{ $prestataire->lastname }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre client --}}
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <select name="client_id" id="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tous</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->firstname }} {{ $client->lastname }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre date début (sur start_time) --}}
            <div>
                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                <input type="date" name="date_debut" id="date_debut" value="{{ request('date_debut') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            {{-- Filtre date fin (sur start_time) --}}
            <div>
                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                <input type="date" name="date_fin" id="date_fin" value="{{ request('date_fin') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <div class="mt-4 flex justify-end space-x-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Filtrer</button>
            <a href="{{ route('rdvs.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Réinitialiser</a>
        </div>
    </form>

    {{-- Tableau des rendez-vous --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('rdvs.index', array_merge(request()->query(), ['order' => 'start_time', 'direction' => request('order') == 'start_time' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1 hover:text-gray-700">
                            <span>Créneau</span>
                            @if(request('order') == 'start_time')
                                <span>{{ request('direction') == 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Client
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Prestataire
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rdvs as $rdv)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $rdv->start_time->format('d/m/Y H:i') }} - {{ $rdv->end_time->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $rdv->client->firstname ?? '' }} {{ $rdv->client->lastname ?? '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $rdv->prestataire->firstname ?? '' }} {{ $rdv->prestataire->lastname ?? '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $rdv->status_text }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('rdvs.edit', $rdv) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                            <form action="{{ route('rdvs.destroy', $rdv) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Aucun rendez-vous à venir.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $rdvs->links() }}
    </div>
</div>
@endsection