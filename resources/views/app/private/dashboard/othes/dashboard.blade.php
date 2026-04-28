@extends('app.private.dashboard.app..templete')


@section('content')

 <main class="flex-1 flex flex-col overflow-hidden">
            
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm">
                <div class="flex items-center bg-gray-100 rounded-lg px-4 py-2 w-96">
                    <i class="fas fa-search text-gray-400"></i>
                    <input type="text" placeholder="Procurar estudante ou anteprojeto..." class="bg-transparent border-none focus:ring-0 text-sm ml-2 w-full">
                </div>
                <div class="flex items-center space-x-6 text-gray-600">
                    <div class="relative">
                        <i class="far fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 w-2 h-2 rounded-full border-2 border-white"></span>
                    </div>
                    <span class="text-sm font-medium">Domingo, 1 Março 2026</span>
                </div>
            </header>
            

            <div class="flex-1 overflow-y-auto p-8 space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-600">
                        <p class="text-sm text-gray-500 font-medium">Campanhas Ativas</p>
                        <h4 class="text-2xl font-bold text-gray-800 mt-2">02</h4>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-yellow-500">
                        <p class="text-sm text-gray-500 font-medium">Pendente Avaliação</p>
                        <h4 class="text-2xl font-bold text-gray-800 mt-2">14</h4>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-green-500">
                        <p class="text-sm text-gray-500 font-medium">TCCs em Curso</p>
                        <h4 class="text-2xl font-bold text-gray-800 mt-2">45</h4>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-red-500">
                        <p class="text-sm text-gray-500 font-medium">Trocas de Tema (Novas)</p>
                        <h4 class="text-2xl font-bold text-gray-800 mt-2">03</h4>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 text-lg">Submissões Recentes de Anteprojeto</h3>
                            <button class="text-blue-600 text-sm font-semibold hover:underline">Ver todas</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-gray-400 text-[11px] uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-4">Estudante</th>
                                        <th class="px-6 py-4">Tema Sugerido</th>
                                        <th class="px-6 py-4">Data</th>
                                        <th class="px-6 py-4">Ação</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr class="hover:bg-blue-50/50 transition">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">Carlos Alberto</p>
                                            <p class="text-xs text-gray-500">Nº 20220455</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-600 truncate w-48">Sistema de Gestão de Vagas...</p>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">Hoje, 09:30</td>
                                        <td class="px-6 py-4">
                                            <button class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700">Avaliar</button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-blue-50/50 transition">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">Maria Luísa</p>
                                            <p class="text-xs text-gray-500">Nº 20220912</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-600 truncate w-48">Segurança em Redes IoT</p>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">Ontem</td>
                                        <td class="px-6 py-4">
                                            <button class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700">Avaliar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-blue-700 text-white p-6 rounded-2xl shadow-lg shadow-blue-200">
                            <h4 class="font-bold text-lg mb-4">Gestão de Membros</h4>
                            <p class="text-blue-100 text-sm mb-4 leading-relaxed">Os utilizadores não podem auto-registar-se. Use o botão abaixo para cadastrar estudantes ou professores.</p>
                            <button class="w-full bg-white text-blue-700 font-bold py-3 rounded-xl hover:bg-blue-50 transition flex items-center justify-center">
                                <i class="fas fa-plus-circle mr-2"></i> Novo Utilizador
                            </button>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <h4 class="font-bold text-gray-800 mb-4">Campanhas Ativas</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                    <div>
                                        <p class="text-xs font-bold text-gray-700">TCC - ENG INFO 2026</p>
                                        <p class="text-[10px] text-gray-500 italic">Termina em 15 dias</p>
                                    </div>
                                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                                </div>
                                <button class="w-full text-blue-600 text-xs font-bold py-2 border border-blue-100 rounded-lg hover:bg-blue-50">
                                    Nova Campanha
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
@endsection