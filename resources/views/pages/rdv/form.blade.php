@php
    $editing = isset($rdv);
@endphp

<div class="space-y-4">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Date</label>
        <x-form.date-picker
            name="date"
            :defaultDate="old('date', $editing ? $rdv->date->format('Y-m-d H:i') : '')"
            dateFormat="Y-m-d H:i"
            minDate="{{ now()->format('Y-m-d H:i') }}"
            required
        />
        @error('date')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Client</label>
        <select name="client_id" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-3 focus:ring-brand-500">
            <option value="">-- choisir un client --</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ old('client_id', $editing ? $rdv->client_id : '') == $client->id ? 'selected' : '' }}>
                    {{ $client->firstname }} {{ $client->lastname }}
                </option>
            @endforeach
        </select>
        @error('client_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Prestataire</label>
        <select name="prestataire_id" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-3 focus:ring-brand-500">
            <option value="">-- choisir un prestataire --</option>
            @foreach($prestataires as $prestataire)
                <option value="{{ $prestataire->id }}" {{ old('prestataire_id', $editing ? $rdv->prestataire_id : '') == $prestataire->id ? 'selected' : '' }}>
                    {{ $prestataire->firstname }} {{ $prestataire->lastname }} ({{ $prestataire->profession }})
                </option>
            @endforeach
        </select>
        @error('prestataire_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
</div>
