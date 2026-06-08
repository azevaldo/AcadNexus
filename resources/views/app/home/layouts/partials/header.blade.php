 <nav class="fixed w-full z-50 border-b border-gray-100 glass" x-data="{ openMobileMenu: false }">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        {{-- LOGO (Sempre visível) --}}
        <div class="flex items-center space-x-2">
            <div class="bg-blue-700 p-2 rounded-lg text-white">
                <i class="fas fa-university text-xl"></i>
            </div>
            <span class="text-xl font-bold text-blue-900 tracking-tight text-2xl">IP ACADÉMICO</span>
        </div>
        
        {{-- LINKS DESKTOP (Ocultos no Mobile) --}}
        <div class="hidden md:flex items-center space-x-8 text-sm font-semibold text-gray-600">
            <a href="#sobre" class="hover:text-blue-700 transition">Sobre o Sistema</a>
         
            <a href="/login" class="bg-blue-700 text-white px-6 py-2.5 rounded-full hover:bg-blue-800 transition shadow-lg shadow-blue-200">
                Aceder ao Portal <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>

        {{-- BOTÃO HAMBÚRGUER MOBILE (Visível apenas em telas pequenas) --}}
        <div class="flex md:hidden">
            <button @click="openMobileMenu = !openMobileMenu" 
                    type="button" 
                    class="text-gray-600 hover:text-blue-700 focus:outline-none p-2 rounded-lg hover:bg-gray-50 transition"
                    aria-label="Toggle menu">
                <i class="fas text-xl" :class="openMobileMenu ? 'fa-times' : 'fa-bars'"></i>
            </button>
        </div>
    </div>

    {{-- MENU RESPONSIVO DROPDOWN (Mobile) --}}
    <div x-show="openMobileMenu" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden bg-white/95 backdrop-blur-md border-b border-gray-100 absolute w-full left-0 px-6 py-6 space-y-4 shadow-xl"
         x-cloak>
        
        <a href="#sobre" @click="openMobileMenu = false" class="block text-base font-semibold text-gray-600 hover:text-blue-700 transition py-2">
            <i class="fas fa-info-circle w-6 text-blue-500"></i> Sobre o Sistema
        </a>
        
        <hr class="border-gray-100">
        
        <a href="/login" class="block w-full text-center bg-blue-700 text-white px-6 py-3 rounded-xl hover:bg-blue-800 transition shadow-lg shadow-blue-200 font-semibold text-sm">
            Aceder ao Portal <i class="fas fa-arrow-right ml-2 text-xs"></i>
        </a>
    </div>
</nav>