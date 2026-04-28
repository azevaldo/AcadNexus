 <!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP ACADÉMICO - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>






<body class="bg-gray-50 font-sans antialiased text-gray-900" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden relative">
        
        <button @click="sidebarOpen = true" 
                class="md:hidden fixed bottom-6 right-6 z-50 bg-blue-700 text-white p-4 rounded-full shadow-2xl active:scale-95 transition-transform">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 w-72 bg-blue-900 text-white flex flex-col shadow-xl transition-transform duration-300 ease-in-out md:relative md:translate-x-0">
            
            @include('app.private.dashboard.layouts.aside')
            
            <button @click="sidebarOpen = false" class="md:hidden absolute top-4 right-4 text-blue-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </aside>

        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black/50 z-30 md:hidden"
             x-transition:enter="transition opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <main class="flex-1 overflow-y-auto bg-gray-50 relative">
            <div class="md:hidden bg-white p-4 flex items-center border-b shadow-sm">
                <span class="font-bold text-blue-900">IP ACADÉMICO</span>
            </div>

            <div class="container mx-auto p-4 md:p-8">
                @yield('content')
            </div>
        </main>

        @include('app.private.dashboard.layouts.logout')
        @include('app.private.dashboard.layouts.components')
    </div>
  
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
       <script>
    function openLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) {
            // Remove o 'hidden' do Tailwind
            modal.classList.remove('hidden');
            // Adiciona 'flex' para que o 'items-center' e 'justify-center' funcionem
            modal.classList.add('flex');
            // Opcional: trava o scroll da página ao fundo
            document.body.style.overflow = 'hidden';
        }
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) {
            // Volta para hidden
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // Libera o scroll
            document.body.style.overflow = 'auto';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast');
    const timer = document.getElementById('toast-timer');

    if (!toast || !timer) return;

    const duration = 15000; // 15 segundos
    let start = null;

    function animate(time) {
        if (!start) start = time;
        const progress = time - start;
        const percent = Math.max(0, 100 - (progress / duration) * 100);

        timer.style.width = percent + '%';

        if (progress < duration) {
            requestAnimationFrame(animate);
        } else {
            toast.classList.add('opacity-0', 'transition', 'duration-500');
            setTimeout(() => toast.remove(), 500);
        }
    }

    requestAnimationFrame(animate);
});
</script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
 
</html>