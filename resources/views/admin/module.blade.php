@extends('admin.layout')
@section('title', $title)
@section('content')
<div class="max-w-3xl">
    <p class="text-sm font-semibold text-amber-700">{{ $section }}</p>
    <h1 class="mt-2 text-4xl font-semibold tracking-tight">{{ $title }}</h1>
    <p class="mt-4 text-stone-500">Modul ini sudah tersedia di navigasi admin dan siap diisi dengan alur {{ strtolower($title) }}.</p>
</div>
<div class="mt-10 border border-dashed border-stone-300 bg-white px-6 py-14 text-center">
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 text-2xl text-amber-700" aria-hidden="true">+</div>
    <h2 class="mt-5 text-lg font-semibold">{{ $title }} belum dikembangkan</h2>
    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-stone-500">Struktur menu sudah disiapkan agar modul ini mudah ditambahkan tanpa mengubah navigasi utama.</p>
    <a href="{{ route('admin.dashboard') }}" class="mt-6 inline-flex rounded-lg bg-stone-900 px-5 py-3 text-sm font-semibold text-white hover:bg-amber-700">Kembali ke dashboard</a>
</div>
@endsection
