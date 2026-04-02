@extends('layouts.dashboard')

@section('title', 'Edit Artikel')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Edit Artikel</h2>
            <p class="text-sm text-slate-500">Perbarui isi artikel dan publikasi kapan saja.</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.articles.partials.form', ['article' => $article])
            </form>
        </div>
    </div>
@endsection
