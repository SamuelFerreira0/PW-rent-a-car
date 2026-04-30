<nav class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">

            <!-- LEFT -->
            <div class="flex items-center gap-8">

                <a href="/" class="font-bold text-lg text-gray-900">
                    Rent a Car
                </a>

                <div class="flex gap-5 text-sm">

                    <a href="/"
                       class="{{ request()->is('/') ? 'text-black font-semibold' : 'text-gray-600 hover:text-black' }}">
                        Dashboard
                    </a>

                    @if(Auth::user()->funcionario)
                        <a href="/veiculos"
                           class="{{ request()->is('veiculos*') ? 'text-black font-semibold' : 'text-gray-600 hover:text-black' }}">
                            Veículos
                        </a>

                        <a href="/reservas"
                           class="{{ request()->is('reservas*') ? 'text-black font-semibold' : 'text-gray-600 hover:text-black' }}">
                            Reservas
                        </a>
                    @endif

                </div>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                <span class="text-sm text-gray-600">
                    {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block py-2 text-red-500">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </div>
</nav>