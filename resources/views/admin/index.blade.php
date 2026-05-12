<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background:#f8f9fb;">
<div class="max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Gestão de Utilizadores</h1>
            <p class="text-sm text-slate-400 mt-0.5">
                {{ $users->count() }} utilizadores ·
                {{ $users->filter->isAdmin()->count() }} admins ·
                {{ $users->filter(fn($u) => !$u->isAdmin() && $u->funcionario)->count() }} funcionários
            </p>
        </div>
        <a href="{{ route('home') }}"
           class="text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-colors">
            ← Dashboard
        </a>
    </div>

    {{-- FEEDBACK --}}
    @if(session('success'))
    <div class="mb-5 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-sm text-green-700 font-medium">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-sm text-red-700 font-medium">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- STATS --}}
    <div class="grid gap-3 mb-6 grid-cols-1 sm:grid-cols-3">
        <div class="bg-white rounded-xl border border-slate-200 p-4"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Total</p>
            <p class="text-xl font-bold text-slate-900">{{ $users->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Funcionários</p>
            <p class="text-xl font-bold text-indigo-600">
                {{ $users->filter(fn($u) => !$u->isAdmin() && $u->funcionario)->count() }}
            </p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Admins</p>
            <p class="text-xl font-bold" style="color:#1e40af;">{{ $users->filter->isAdmin()->count() }}</p>
        </div>
    </div>

    {{-- LISTA --}}
    <div class="flex flex-col gap-2.5">
        @foreach($users as $user)
        @php
            $isSelf    = $user->id === auth()->id();
            $isAdmin   = $user->isAdmin();
            $isFunc    = !$isAdmin && $user->funcionario;
            $temReservas = $user->cliente && ($user->cliente->reservas_count ?? 0) > 0;
        @endphp

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden transition-all duration-150"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">

            {{-- CORPO --}}
            <div class="flex items-center gap-4 p-5">

                {{-- AVATAR --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-sm font-bold text-white"
                     style="background:{{ $isAdmin ? 'linear-gradient(135deg,#1e40af,#1d4ed8)' : ($isFunc ? 'linear-gradient(135deg,#4f46e5,#6366f1)' : 'linear-gradient(135deg,#94a3b8,#cbd5e1)') }};">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                {{-- INFO --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-[15px] font-semibold text-slate-900 tracking-tight">
                            {{ $user->name }}
                            @if($isSelf)
                                <span class="text-[11px] font-normal text-slate-400">(você)</span>
                            @endif
                        </p>
                        @if($isAdmin)
                            <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full text-white border border-blue-800"
                                  style="background:linear-gradient(135deg,#1e40af,#1d4ed8);">
                                ADMIN
                            </span>
                        @elseif($isFunc)
                            <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                FUNCIONÁRIO
                            </span>
                        @else
                            <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 border border-slate-200">
                                CLIENTE
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $user->email }}</p>
                </div>

            </div>

            {{-- AÇÕES --}}
            @if(!$isAdmin && !$isSelf)
            <div class="flex items-center gap-1 px-4 py-2.5 border-t border-slate-100" style="background:#fafbfc;">

                @if($isFunc)
                    <form method="POST" action="{{ route('admin.revogar', $user->id) }}">
                        @csrf @method('PUT')
                        <button type="submit"
                                class="text-xs font-medium text-amber-600 hover:text-amber-800 hover:bg-amber-50 px-3 py-1.5 rounded-lg transition-colors"
                                onclick="return confirm('Remover permissões de funcionário de ' + @json($user->name) + '?')">
                            Revogar funcionário
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.promover', $user->id) }}">
                        @csrf @method('PUT')
                        <button type="submit"
                                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">
                            Promover a funcionário
                        </button>
                    </form>

                    @if(!$temReservas)
                    <form method="POST" action="{{ route('admin.destroy', $user->id) }}" class="ml-auto">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-xs font-medium text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors"
                                onclick="return confirm('Eliminar a conta de ' + @json($user->name) + '? Esta acção é irreversível.')">
                            Eliminar
                        </button>
                    </form>
                    @endif
                @endif

            </div>
            @endif

        </div>
        @endforeach
    </div>

</div>
</div>
</x-app-layout>
