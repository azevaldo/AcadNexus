<div id="logoutModal" onclick="closeLogoutModal()"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

    <div onclick="event.stopPropagation()" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 animate-fade-in">

        <div class="flex justify-center mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5m0 0a4 4 0 014 4v6a4 4 0 01-4 4" />
                </svg>
            </div>
        </div>

        <h2 class="text-lg font-bold text-gray-800 text-center mb-2">
            Terminar sessão
        </h2>
        <p class="text-sm text-gray-500 text-center mb-6">
            Tens a certeza que desejas sair da tua conta?
        </p>

        <div class="flex justify-end gap-3">

            <button onclick="closeLogoutModal()"
                class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-bold
                           hover:bg-gray-100 transition">
                Cancelar
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="px-4 py-2 rounded-xl bg-red-600 text-white font-bold
                               hover:bg-red-700 transition">
                    Sair
                </button>
            </form>

        </div>
    </div>
</div>
