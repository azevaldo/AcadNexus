<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP ACADÉMICO | Portal Institucional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .hero-pattern { background-color: #f8fafc; background-image: radial-gradient(#1e40af 0.5px, transparent 0.5px); background-size: 24px 24px; }
    </style>
</head>
<body class="font-sans text-gray-900 bg-white">

    @include('app.home.layouts.partials.header')

   <main class="pt-20"> @yield('content')
</main>

    @include('app.home.layouts.partials.footer')
   @include('app.home.layouts.partials.logout')
       @include('app.home.layouts.partials.components')
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
</body>
</html>