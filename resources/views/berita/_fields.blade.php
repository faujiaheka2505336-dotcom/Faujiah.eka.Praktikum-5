{{-- Field form bersama. $berita = null saat create, model saat edit. --}}
{{-- Variabel $kategoris dan $tags dikirim oleh BeritaController::create()/edit(). --}}
<div class="mb-3">
    <label for="judul" class="form-label fw-semibold">Judul</label>
    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror"
           value="{{ old('judul', $berita?->judul) }}" placeholder="Judul berita...">
    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="kategori_id" class="form-label fw-semibold">Kategori</label>
        <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategoris as $k)
                <option value="{{ $k->id }}" @selected(old('kategori_id', $berita?->kategori_id) == $k->id)>{{ $k->nama }}</option>
            @endforeach
        </select>
        @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="published_at" class="form-label fw-semibold">Tanggal Terbit</label>
        <input type="datetime-local" name="published_at" id="published_at"
               class="form-control @error('published_at') is-invalid @enderror"
               value="{{ old('published_at', $berita?->published_at?->format('Y-m-d\TH:i')) }}">
        <div class="form-text">Kosongkan untuk memakai waktu sekarang.</div>
        @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

@php
    // Setelah validasi gagal, pakai centang terakhir dari input pengguna (termasuk bila semua
    // tag dikosongkan). Selain itu: tag milik berita (edit) atau kosong (create).
    $tagDipilih = session()->hasOldInput()
        ? (array) old('tags', [])
        : ($berita?->tags->pluck('id')->all() ?? []);
@endphp

<div class="mb-3">
    <label class="form-label fw-semibold d-block">Tag</label>
    @forelse ($tags as $tag)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="tags[]"
                   id="tag{{ $tag->id }}" value="{{ $tag->id }}"
                   @checked(in_array($tag->id, $tagDipilih))>
            <label class="form-check-label" for="tag{{ $tag->id }}">#{{ $tag->nama }}</label>
        </div>
    @empty
        <span class="text-muted small">Belum ada tag.</span>
    @endforelse
    @error('tags') <div class="text-danger small">{{ $message }}</div> @enderror
    @error('tags.*') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="gambar" class="form-label fw-semibold">Gambar</label>
    @if ($berita?->gambar)
        <div class="mb-2"><img src="{{ $berita->gambar_url }}" alt="" style="height:90px;border-radius:8px;object-fit:cover"></div>
    @endif
    <input type="file" name="gambar" id="gambar" accept="image/*"
           class="form-control @error('gambar') is-invalid @enderror">
    <div class="form-text">JPG, PNG, atau WebP, maksimal 2 MB.{{ $berita?->gambar ? ' Kosongkan jika tidak ingin mengganti.' : '' }}</div>
    @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="isi" class="form-label fw-semibold">Isi Berita</label>
    <textarea name="isi" id="isi" rows="8" class="form-control @error('isi') is-invalid @enderror"
              placeholder="Tulis isi berita di sini...">{{ old('isi', $berita?->isi) }}</textarea>
    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
