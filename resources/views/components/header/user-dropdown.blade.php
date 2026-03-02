@php
use Illuminate\Support\Facades\Auth;
@endphp

<div class="relative">
    
    <!-- Bouton utilisateur -->
    <button id="userDropdownButton"
        class="flex items-center text-gray-700 dropdown-toggle dark:text-gray-400">
        
        <span class="mr-2 font-medium text-theme-sm">
            {{ Auth::user()->username ?? 'Utilisateur' }}
        </span>

        <svg
            class="stroke-gray-500 dark:stroke-gray-400"
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none">
            <path d="M6 9L12 15L18 9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <!-- Menu dropdown -->
    <div id="userDropdown"
        class="absolute right-0 mt-2 w-56 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800 hidden">

        <!-- Info utilisateur -->
        <div class="px-4 py-3">
            <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">
                {{ Auth::user()->username ?? 'Utilisateur' }}
            </span>

            <span class="mt-0.5 block text-theme-xs text-gray-500 dark:text-gray-400">
                {{ Auth::user()->email ?? '' }}
            </span>
        </div>

        <!-- Menu -->
        <ul class="py-2">
            <li>
                <a href="#"
                   class="flex px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    Profil
                </a>
            </li>

            <li>
                <a href="#"
                   class="flex px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    Paramètres
                </a>
            </li>
        </ul>

        <!-- Logout -->
        <div class="border-t border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Se déconnecter
                </button>
            </form>
        </div>

    </div>
</div>

<script>
document.getElementById('userDropdownButton').addEventListener('click', function () {
    document.getElementById('userDropdown').classList.toggle('hidden');
});
</script>