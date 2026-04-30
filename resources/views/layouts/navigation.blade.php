<nav class="bg-white border-b border-slate-200">
    <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between">

        {{-- LEFT: LOGO + LINKS --}}
        <div class="flex items-center gap-6">

            <a href="/" class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#1e40af;">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-bold text-slate-900 tracking-tight">Rent a Car</span>
            </a>

            <div class="flex items-center gap-1">

                <a href="/"
                   class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors
                          {{ request()->is('/') ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Dashboard
                </a>

                @if(Auth::user()->funcionario)
                    <a href="/veiculos"
                       class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors
                              {{ request()->is('veiculos*') ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                        Veículos
                    </a>

                    <a href="/reservas"
                       class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors
                              {{ request()->is('reservas*') ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                        Reservas
                    </a>
                @endif

            </div>
        </div>

        {{-- RIGHT: NOME + LOGOUT --}}
        <div class="flex items-center gap-3">

            <span class="text-xs text-slate-400 font-medium">{{ Auth::user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-xs font-semibold text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">
                    Logout
                </button>
            </form>

        </div>

    </div>
</nav>