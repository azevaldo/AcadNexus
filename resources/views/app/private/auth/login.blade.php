 @extends('app.private.auth.app')

@section('content')

<div class="max-w-md w-full relative">

    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-700 text-white rounded-2xl shadow-lg mb-4">
            <i class="fas fa-university text-2xl"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-blue-950 tracking-tight">IP ACADÉMICO</h1>
        <p class="text-gray-500 mt-2 font-medium">
            Portal de Gestão de Temas e TCC
        </p>
    </div>

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white p-8 md:p-10">

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-blue-950">
                    E-mail Institucional
                </label>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="far fa-envelope"></i>
                    </span>

                    <input
                        type="email"
                        name="email"
                        required
                        value="{{ old('email') }}"
                        placeholder="exemplo@isp.ao"
                        class="block w-full pl-10 pr-4 py-3 bg-white border rounded-xl
                               focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all
                               @error('email') border-red-500 @else border-gray-200 @enderror"
                    />
                </div>

                @error('email')
                    <p class="text-red-500 text-xs font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label class="block text-sm font-semibold text-blue-950">
                        Palavra-passe
                    </label>
                    <a href="#" class="text-xs font-bold text-blue-700 hover:underline">
                        Esqueceu-se?
                    </a>
                </div>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="block w-full pl-10 pr-12 py-3 bg-white border rounded-xl
                               focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all
                               @error('password') border-red-500 @else border-gray-200 @enderror"
                    />

                    <button
                        type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-blue-700 transition"
                    >
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3.5 rounded-xl
                       shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-0.5
                       active:scale-[0.98] flex items-center justify-center"
            >
                <span>Iniciar Sessão</span>
                <i class="fas fa-sign-in-alt ml-2"></i>
            </button>

        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">
                Acesso Seguro • IP Académico
            </p>
        </div>
    </div>

    <div class="mt-6 flex flex-col gap-3 items-center">
        
        <div class="text-sm font-medium text-gray-600">
            Ainda não tem conta?
            <a href="/create_estudante" class="text-blue-700 font-bold hover:underline ml-1">
                Cadastrar como Estudante
            </a>
        </div>

        <div class="flex items-center gap-2">
            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
            <a href="{{ route('home') }}" class="text-xs font-bold text-gray-400 hover:text-blue-700 transition-colors uppercase tracking-tighter flex items-center gap-1">
                <i class="fas fa-arrow-left text-[10px]"></i>
                Voltar à Página Inicial
            </a>
            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
        </div>
        
    </div>

    <p class="text-center mt-10 text-[10px] text-gray-400 font-medium uppercase tracking-widest">
        &copy; 2026 IP ACADÉMICO. Todos os direitos reservados.
    </p>

</div>

<script>
    function togglePassword(id, el) {
        const input = document.getElementById(id);
        const icon = el.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

@endsection