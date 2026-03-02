<div class="space-y-4">
    <div>
        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Début du rendez-vous</label>
        <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time', isset($rdv) ? $rdv->start_time->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white" required>
        @error('start_time')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fin du rendez-vous</label>
        <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time', isset($rdv) ? $rdv->end_time->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white" required>
        @error('end_time')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client</label>
        <select name="client_id" id="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white" required>
            <option value="">Sélectionnez un client</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ old('client_id', isset($rdv) ? $rdv->client_id : '') == $client->id ? 'selected' : '' }}>
                    {{ $client->firstname }} {{ $client->lastname }}
                </option>
            @endforeach
        </select>
        @error('client_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="prestataire_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prestataire</label>
        <select name="prestataire_id" id="prestataire_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white" required>
            <option value="">Sélectionnez un prestataire</option>
            @foreach($prestataires as $prestataire)
                <option value="{{ $prestataire->id }}" {{ old('prestataire_id', isset($rdv) ? $rdv->prestataire_id : '') == $prestataire->id ? 'selected' : '' }}>
                    {{ $prestataire->firstname }} {{ $prestataire->lastname }}
                </option>
            @endforeach
        </select>
        @error('prestataire_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
