<nav class="bg-white border-b border-slate-200 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
    <div class="max-w-4xl mx-auto px-4 h-14 flex items-center justify-between">

        {{-- LOGO + LINKS --}}
        <div class="flex items-center gap-6">

            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 transition-transform duration-150 group-hover:-translate-y-px"
                     style="background:linear-gradient(135deg,#2563eb,#1e40af); box-shadow:0 2px 6px rgba(30,64,175,0.35);">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-bold text-slate-900 tracking-tight">Rent a Car</span>
            </a>

            <div class="flex items-center gap-0.5">

                <a href="{{ route('home') }}"
                   class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150
                          {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Dashboard
                </a>

                <a href="{{ route('reservas.index') }}"
                   class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150
                          {{ request()->routeIs('reservas.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Reservas
                </a>

                <a href="{{ route('catalogo.index') }}"
                   class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150
                          {{ request()->routeIs('catalogo.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Catálogo
                </a>

                @if(Auth::user()->isFuncionarioOrAdmin())
                    <a href="{{ route('veiculos.index') }}"
                       class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150
                              {{ request()->routeIs('veiculos.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                        Veículos
                    </a>
                @endif

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.index') }}"
                       class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150
                              {{ request()->routeIs('admin.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                        Admin
                    </a>
                @endif

            </div>
        </div>

        {{-- AVATAR + LOGOUT --}}
        <div class="flex items-center gap-3">

            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold text-white flex-shrink-0"
                     style="background:linear-gradient(135deg,#2563eb,#1e40af);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="text-xs text-slate-500 font-medium">{{ Auth::user()->name }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-xs font-semibold text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-all duration-150">
                    Sair
                </button>
            </form>

        </div>

    </div>
</nav>
