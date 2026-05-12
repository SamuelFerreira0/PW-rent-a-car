<x-guest-layout>

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Criar conta</h2>
        <p class="text-sm text-slate-400 mt-1">Preenche os dados para te registares</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2" for="name">
                Nome
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   class="w-full px-4 py-3 text-sm text-slate-800 bg-white rounded-xl transition-all duration-150
                          border shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)]
                          focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                          {{ $errors->has('name') ? 'border-red-300' : 'border-slate-200' }}">
            @error('name')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2" for="email">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="username"
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
                   required autocomplete="new-password"
                   class="w-full px-4 py-3 text-sm text-slate-800 bg-white rounded-xl transition-all duration-150
                          border shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)]
                          focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                          {{ $errors->has('password') ? 'border-red-300' : 'border-slate-200' }}">
            @error('password')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2" for="password_confirmation">
                Confirmar password
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required autocomplete="new-password"
                   class="w-full px-4 py-3 text-sm text-slate-800 bg-white rounded-xl transition-all duration-150
                          border shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)]
                          focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                          {{ $errors->has('password_confirmation') ? 'border-red-300' : 'border-slate-200' }}">
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-3 text-sm font-semibold text-white rounded-xl transition-all duration-150
                       hover:-translate-y-px active:translate-y-0"
                style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%);
                       box-shadow:0 2px 8px rgba(30,64,175,0.25);">
            Criar conta
        </button>

    </form>

    <p class="mt-6 text-center text-sm text-slate-400">
        Já tens conta?
        <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-800 transition-colors">
            Entra aqui
        </a>
    </p>

</x-guest-layout>
