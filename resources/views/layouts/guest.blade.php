<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rent a Car') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background:#f8f9fb;">

        <div class="min-h-screen flex">

            {{-- LEFT BRAND PANEL --}}
            <div class="hidden lg:flex flex-col justify-between w-[420px] flex-shrink-0 p-12 relative overflow-hidden"
                 style="background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 60%,#1d4ed8 100%);">

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="background:rgba(255,255,255,0.15);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-base tracking-tight">Rent a Car</span>
                </div>

                <div>
                    <h1 class="text-white text-3xl font-bold tracking-tight leading-tight mb-3">
                        Gestão de frotas<br>simples e eficaz
                    </h1>
                    <p class="text-blue-200 text-sm leading-relaxed">
                        Reservas, veículos e utilizadores num só lugar. Controlo total da tua frota em tempo real.
                    </p>

                    <div class="mt-8 flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(255,255,255,0.1);">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm text-blue-100">Criação rápida de reservas</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(255,255,255,0.1);">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm text-blue-100">Controlo de conflitos automático</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(255,255,255,0.1);">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm text-blue-100">Gestão de papéis e permissões</span>
                        </div>
                    </div>
                </div>

                <p class="text-blue-300 text-xs">© {{ date('Y') }} Rent a Car</p>

                <div class="absolute -bottom-24 -right-24 w-64 h-64 rounded-full opacity-10"
                     style="background:radial-gradient(circle,#ffffff,transparent)"></div>
                <div class="absolute top-32 -right-16 w-48 h-48 rounded-full opacity-5"
                     style="background:radial-gradient(circle,#ffffff,transparent)"></div>
            </div>

            {{-- RIGHT FORM PANEL --}}
            <div class="flex-1 flex items-center justify-center p-8">
                <div class="w-full max-w-sm">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
