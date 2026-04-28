@if(session('success') || session('error'))
<div
    id="toast"
    class="fixed bottom-6 right-6 z-50 w-80 bg-white border-l-4
           {{ session('success') ? 'border-green-600' : 'border-red-600' }}
           rounded-xl shadow-2xl overflow-hidden animate-slide-in">

    <div class="p-4">
        <p class="text-sm font-semibold
            {{ session('success') ? 'text-green-700' : 'text-red-700' }}">
            {{ session('success') ?? session('error') }}
        </p>
    </div>

    <!-- Barra de tempo -->
    <div class="h-1 bg-gray-200">
        <div
            id="toast-timer"
            class="h-full
            {{ session('success') ? 'bg-green-600' : 'bg-red-600' }}">
        </div>
    </div>
</div>
@endif
