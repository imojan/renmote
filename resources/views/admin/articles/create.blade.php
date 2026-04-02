@extends('layouts.dashboard')

@section('title', 'Tambah Artikel')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Tambah Artikel Baru</h2>
            <p class="text-sm text-slate-500">Artikel akan tampil di beranda ketika status publikasi aktif.</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.articles.partials.form')
            </form>
        </div>
    </div>
@endsection
