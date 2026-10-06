@extends('admin.layout')

@section('title', 'Log Aktivitas')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-semibold text-amber-700">
                Audit trail
            </p>

            <h1 class="mt-2 text-4xl font-semibold tracking-tight">
                Log aktivitas
            </h1>

            <p class="mt-3 text-sm text-stone-500">
                Riwayat perubahan order, pembayaran, stok, dan upload bukti.
            </p>
        </div>

        <a
            href="{{ route('admin.orders.index') }}"
            class="text-sm font-semibold text-stone-600 hover:text-amber-700"
        >
            ← Kembali ke pesanan
        </a>
    </div>

    {{-- Activity List --}}
    <div class="mt-8 divide-y divide-stone-200 border-y border-stone-200 bg-white">
        @forelse ($activities as $activity)
            <div
                class="flex flex-col gap-3 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-6"
            >
                {{-- Activity Information --}}
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800"
                        >
                            {{ str_replace('_', ' ', ucfirst($activity->event)) }}
                        </span>

                        @if ($activity->order)
                            <a
                                href="{{ route('admin.orders.index') }}"
                                class="text-sm font-semibold text-stone-800 hover:text-amber-700"
                            >
                                {{ $activity->order->order_number }}
                            </a>
                        @endif
                    </div>

                    <p class="mt-3 text-sm text-stone-600">
                        {{ $activity->description ?: 'Aktivitas order tercatat.' }}
                    </p>

                    @if ($activity->from_status || $activity->to_status)
                        <p class="mt-1 text-xs text-stone-400">
                            {{ $activity->from_status ?: 'mulai' }}
                            →
                            {{ $activity->to_status ?: '-' }}
                        </p>
                    @endif
                </div>

                {{-- Activity Metadata --}}
                <div class="text-left text-xs text-stone-400 sm:text-right">
                    <p>
                        {{ $activity->created_at?->format('d M Y, H:i:s') }}
                    </p>

                    <p class="mt-1">
                        {{ $activity->actor_type ?: 'system' }}

                        @if ($activity->actor)
                            · {{ $activity->actor->name }}
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-sm text-stone-500">
                Belum ada aktivitas tercatat.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $activities->links() }}
    </div>
@endsection
