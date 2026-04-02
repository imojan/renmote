@php
    $isEdit = isset($article);
@endphp

@if($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid grid-cols-1 gap-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Artikel <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required
               class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
               placeholder="Contoh: Tips Touring Motor Aman Saat Musim Hujan">
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Ringkasan (excerpt)</label>
        <textarea name="excerpt" rows="3"
                  class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                  placeholder="Ringkasan singkat artikel (maksimal 320 karakter)">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Isi Artikel <span class="text-red-500">*</span></label>
        <textarea name="content" rows="14" required
                  class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                  placeholder="Tulis isi artikel lengkap di sini...">{{ old('content', $article->content ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Cover Artikel</label>

        @if($isEdit && $article->cover_image)
            <div class="mb-3">
                <img src="{{ Storage::url($article->cover_image) }}" alt="{{ $article->title }}" class="w-full max-w-sm h-44 object-cover rounded-xl border border-slate-200">
            </div>
        @endif

        <input type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp"
               class="w-full text-sm text-slate-700 border border-slate-300 rounded-xl p-2">
        <p class="text-xs text-slate-500 mt-1">Format JPG/PNG/WEBP, maksimal 3MB.</p>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Berita Dibuat (Opsional)</label>
        <input
            type="datetime-local"
            name="published_at"
            value="{{ old('published_at', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d\\TH:i') : '') }}"
            class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
        >
        <p class="text-xs text-slate-500 mt-1">Isi jika ingin memakai tanggal dan jam asli dari sumber berita.</p>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" id="is_published" name="is_published" value="1"
               @checked(old('is_published', $article->is_published ?? true))
               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
        <label for="is_published" class="text-sm font-medium text-slate-700">Publikasikan artikel</label>
    </div>
</div>

<div class="flex items-center justify-end gap-3 mt-6">
    <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">
        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Artikel' }}
    </button>
</div>
