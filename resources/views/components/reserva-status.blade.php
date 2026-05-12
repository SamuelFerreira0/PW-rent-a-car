@props(['reserva'])

@php
    [$text, $colors, $dot] = match(true) {
        $reserva->isAtiva()     => ['Ativa',     'border-emerald-200 bg-emerald-50 text-emerald-700', 'bg-emerald-500'],
        $reserva->isConcluida() => ['Concluída', 'border-violet-200 bg-violet-50 text-violet-700',   'bg-violet-400'],
        default                 => ['Cancelada', 'border-red-200 bg-red-50 text-red-600',             'bg-red-400'],
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 text-[11px] font-semibold rounded-full border $colors"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>{{ $text }}
</span>
