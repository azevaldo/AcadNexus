<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarAcessoUsuario
{
    public function handle(Request $request, Closure $next)
    {
        // Se o usuário estiver autenticado e a coluna 'acesso' for falsa/0
        if (Auth::check() && !Auth::user()->acesso) {
            
            // Faz o logout imediatamente
            Auth::logout();

            // Invalida a sessão atual por segurança
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redireciona para o login com uma mensagem explicativa
            return redirect()->route('login')->with('error', 'A sua conta está suspensa. Por favor, contacte a administração.');
        }

        return $next($request);
    }
}