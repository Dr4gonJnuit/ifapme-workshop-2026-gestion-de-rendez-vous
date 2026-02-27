@extends('layouts.app')

@section('content')

<!-- FLASH MESSAGES -->
<div class="fixed top-4 right-4 z-[9999] space-y-2">
    @if(session('success'))
        <div id="flash-success" class="bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div id="flash-error" class="bg-red-500 text-white px-4 py-2 rounded shadow-lg">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
</div>

<script>
    setTimeout(() => { const flash = document.getElementById('flash-success'); if(flash) flash.remove(); }, 3000);
    setTimeout(() => { const flashErr = document.getElementById('flash-error'); if(flashErr) flashErr.remove(); }, 4000);
</script>

<!-- CONTENU PRINCIPAL -->
<div class="grid grid-cols-12 gap-4 md:gap-6">

    <!-- Prestataires Table -->
    <div class="col-span-12 space-y-6">

        <!-- Header + recherche -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl text-gray-400 font-bold">Liste des Prestataires</h1>

            <div class="flex gap-2">
                <!-- Formulaire recherche -->
                <form method="GET" action="{{ route('prestataire.index') }}" class="flex">
                    <input type="text" name="q" placeholder="Rechercher..." value="{{ request('q') }}"
                        class="border rounded-l-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition">
                        🔍
                    </button>
                </form>

                <button onclick="openAddModal()"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    + Ajouter un prestataire
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3">Prénom</th>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Téléphone</th>
                        <th class="px-4 py-3">Profession</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestataires as $prestataire)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $prestataire->firstname }}</td>
                            <td class="px-4 py-3">{{ $prestataire->lastname }}</td>
                            <td class="px-4 py-3">{{ $prestataire->email }}</td>
                            <td class="px-4 py-3">{{ $prestataire->phone }}</td>
                            <td class="px-4 py-3">{{ $prestataire->profession }}</td>
                            <td class="px-4 py-3 flex gap-2">
                                <button title="Modifier" onclick="openEditModal({{ json_encode($prestataire) }})" class="p-2 bg-yellow-100 rounded hover:bg-yellow-200 transition">✎</button>
                                <button onclick="openDeleteModal('{{ route('prestataire.destroy', $prestataire->id) }}')" title="Supprimer" class="p-2 bg-red-100 rounded hover:bg-red-200 transition">✕</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun prestataire trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
            {{ $prestataires->links() }}
        </div>

    </div>
</div>

<!-- MODALS -->

<!-- Ajouter -->
<div id="addPrestataireModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 relative">
        <h2 class="text-xl font-bold mb-4">Ajouter un prestataire</h2>
        <button onclick="closeAddModal()" class="absolute top-3 right-3 text-gray-500 hover:text-black">✕</button>
        <form method="POST" action="{{ route('prestataire.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-medium">Prénom</label>
                <input type="text" name="firstname" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Nom</label>
                <input type="text" name="lastname" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Profession</label>
                <input type="text" name="profession" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Téléphone</label>
                <input type="text" name="phone" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Éditer -->
<div id="editPrestataireModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 relative">
        <h2 class="text-xl font-bold mb-4">Modifier un prestataire</h2>
        <button onclick="closeEditModal()" class="absolute top-3 right-3 text-gray-500 hover:text-black">✕</button>
        <form id="editPrestataireForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1 font-medium">Prénom</label>
                <input type="text" name="firstname" id="editFirstname" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Nom</label>
                <input type="text" name="lastname" id="editLastname" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Profession</label>
                <input type="text" name="profession" id="editProfession" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" id="editEmail" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Téléphone</label>
                <input type="text" name="phone" id="editPhone" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

<!-- Supprimer -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative">
        <h2 class="text-xl font-bold mb-4">Confirmer la suppression</h2>
        <p class="mb-6">Êtes-vous sûr de vouloir supprimer ce prestataire ? Cette action est irréversible.</p>
        <div class="flex justify-end gap-4">
            <button onclick="closeDeleteModal()" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 transition">Annuler</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">OK, supprimer</button>
            </form>
        </div>
        <button onclick="closeDeleteModal()" class="absolute top-3 right-3 text-gray-500 hover:text-black">✕</button>
    </div>
</div>

<script>
/* Modals Ajouter */
function openAddModal() {
    document.getElementById('addPrestataireModal').classList.remove('hidden');
    document.getElementById('addPrestataireModal').classList.add('flex');
}
function closeAddModal() {
    document.getElementById('addPrestataireModal').classList.add('hidden');
    document.getElementById('addPrestataireModal').classList.remove('flex');
}

/* Modals Editer */
function openEditModal(prestataire) {
    document.getElementById('editFirstname').value = prestataire.firstname;
    document.getElementById('editLastname').value = prestataire.lastname;
    document.getElementById('editProfession').value = prestataire.profession;
    document.getElementById('editEmail').value = prestataire.email;
    document.getElementById('editPhone').value = prestataire.phone;
    document.getElementById('editPrestataireForm').action = '/prestataire/' + prestataire.id;
    document.getElementById('editPrestataireModal').classList.remove('hidden');
    document.getElementById('editPrestataireModal').classList.add('flex');
}
function closeEditModal() {
    document.getElementById('editPrestataireModal').classList.add('hidden');
    document.getElementById('editPrestataireModal').classList.remove('flex');
}

/* Modal Supprimer */
function openDeleteModal(url) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = url;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

@endsection