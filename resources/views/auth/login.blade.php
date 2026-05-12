<x-guest-layout>

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Bem-vindo de volta</h2>
        <p class="text-sm text-slate-400 mt-1">Entra na tua conta para continuar</p>
    </div>

    @if(session('status'))
    <div class="mb-5 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-sm text-green-700 font-medium">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2" for="email">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   class="w-full px-4 py-3 text-sm text-slate-800 bg-white rounded-xl transition-all duration-150
                          border shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)]
                          focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                          {{ $errors->has('email') ? 'border-red-300' : 'border-slate-200' }}">
            @error('email')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2" for="password">
                Password
            </label>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password"
                   class="w-full px-4 py-3 text-sm text-slate-800 bg-white rounded-xl transition-all duration-150
                          border shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)]
                          focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                          {{ $errors->has('password') ? 'border-red-300' : 'border-slate-200' }}">
            @error('password')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                <span class="text-sm text-slate-500">Lembrar-me</span>
            </label>

            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors">
                    Esqueci a password
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-3 text-sm font-semibold text-white rounded-xl transition-all duration-150
                       hover:-translate-y-px active:translate-y-0"
                style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%);
                       box-shadow:0 2px 8px rgba(30,64,175,0.25);">
            Entrar
        </button>

    </form>

    <p class="mt-6 text-center text-sm text-slate-400">
        Não tens conta?
        <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-800 transition-colors">
            Regista-te
        </a>
    </p>

</x-guest-layout>
