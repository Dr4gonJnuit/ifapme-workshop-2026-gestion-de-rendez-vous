@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Gestion des rendez-vous" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Liste des rendez-vous</h3>
            <a href="{{ route('rdvs.create') }}" class="inline-flex items-center rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                Nouveau rendez-vous
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded bg-green-100 px-4 py-2 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded bg-red-100 px-4 py-2 text-red-800">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-100 text-left text-xs font-semibold uppercase text-gray-600">
                    <tr>
                        <th class="px-3 py-2">Date</th>
                        <th class="px-3 py-2">Client</th>
                        <th class="px-3 py-2">Prestataire</th>
                        <th class="px-3 py-2">Statut</th>
                        <th class="px-3 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($rdvs as $rdv)
                        <tr class="border-t">
                            <td class="px-3 py-2">{{ $rdv->date->format('Y-m-d H:i') }}</td>
                            <td class="px-3 py-2">{{ optional($rdv->client)->firstname }} {{ optional($rdv->client)->lastname }}</td>
                            <td class="px-3 py-2">{{ optional($rdv->prestataire)->firstname }} {{ optional($rdv->prestataire)->lastname }}</td>
                            <td class="px-3 py-2">
                                <form method="POST" action="{{ route('rdvs.updateStatus', $rdv) }}" class="flex items-center space-x-2 status-form" data-current-status="{{ $rdv->status_text }}" data-rdv-id="{{ $rdv->id }}">
                                    @csrf
                                    <select name="status_text" class="h-8 rounded border-gray-300 status-select" required>
                                        <option value="à venir" {{ $rdv->status_text === 'à venir' ? 'selected' : '' }}>à venir</option>
                                        <option value="passé" {{ $rdv->status_text === 'passé' ? 'selected' : '' }}>passé</option>
                                    </select>
                                    <button type="submit" class="text-blue-600 hover:underline text-sm status-btn">Actualiser</button>
                                </form>
                            </td>
                            <td class="px-3 py-2 space-x-2">
                                <a href="{{ route('rdvs.edit', $rdv) }}" class="text-indigo-600 hover:underline">Modifier</a>
                                <form class="inline" method="POST" action="{{ route('rdvs.destroy', $rdv) }}" onsubmit="return confirm('Êtes-vous sûr(e) ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-2 text-center text-gray-500">Aucun rendez-vous trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $rdvs->links() }}
        </div>
    </div>

    <script>
        document.querySelectorAll('.status-form').forEach(form => {
            const select = form.querySelector('.status-select');
            const currentStatus = form.dataset.currentStatus;
            const rDVId = form.dataset.rdvId;

            form.addEventListener('submit', function(e) {
                const newStatus = select.value;
                if (currentStatus === 'passé' && newStatus === 'à venir') {
                    const confirmed = confirm(
                        'Attention : Reverser ce rendez-vous de "passé" à "\u00e0 venir" peut être bloqué ' +
                        'si un autre rendez-vous "à venir" existe déjà dans une plage de ±15 minutes.\n\n' +
                        'Voulez-vous continuer ?'
                    );
                    if (!confirmed) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
@endsection
