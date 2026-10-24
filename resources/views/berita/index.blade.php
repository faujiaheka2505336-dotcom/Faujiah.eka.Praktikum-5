@extends('layouts.app')

@section('title', 'Berita Desa')

@section('content')
<div class="row">

    {{-- Filter --}}
    <div class="col-md-3 mb-4">
        <div class="panel">
            <div class="panel-head"><i class="bi bi-funnel-fill me-1"></i> Filter Berita</div>
            <form method="GET" action="{{ route('berita.index') }}" class="p-3">
                <label for="q" class="form-label fw-semibold">Kata Kunci</label>
                <input type="text" id="q" name="q" class="form-control mb-3"
                       value="{{ request('q') }}" placeholder="Cari berita...">

                <label for="kategori" class="form-label fw-semibold">Kategori</label>
                <select id="kategori" name="kategori" class="form-select mb-3">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary w-100 mb-2">Cari</button>
                <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </form>
        </div>

        @auth
            <a href="{{ route('berita.create') }}" class="btn btn-success w-100 mt-3">+ Tambah Berita</a>
        @endauth
    </div>

    {{-- Daftar berita --}}
    <div class="col-md-9">
        <div class="row">
            @forelse ($beritas as $berita)
                <div class="col-md-4 mb-4">
                    <div class="card card-berita h-100">
                        <a href="{{ route('berita.show', $berita) }}" class="thumb-wrap">
                            <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="thumb" loading="lazy"
                                 onerror="this.onerror=null;this.src='{{ \App\Models\Berita::PLACEHOLDER }}'">
                        </a>

                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge badge-kategori">{{ $berita->kategori->nama }}</span>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> {{ $berita->published_at?->translatedFormat('d M Y') }}
                                </small>
                            </div>

                            <h6>
                                <a href="{{ route('berita.show', $berita) }}" class="text-decoration-none text-dark fw-bold">
                                    {{ Str::limit($berita->judul, 70) }}
                                </a>
                            </h6>

                            <p class="text-muted small flex-grow-1">{{ Str::limit(strip_tags($berita->isi), 90) }}</p>

                            <div class="mb-2">
                                @foreach ($berita->tags as $tag)
                                    <a href="{{ route('berita.index', ['tag' => $tag->id]) }}" class="tag-pill">#{{ $tag->nama }}</a>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted"><i class="bi bi-eye"></i> {{ number_format($berita->dilihat) }}</small>
                                <a href="{{ route('berita.show', $berita) }}" class="btn btn-sm btn-baca">
                                    Baca <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                            {{-- Hanya mengatur tampilan; perlindungan sebenarnya ada pada middleware di routes/web.php --}}
                            @auth
                                <div class="mt-2 pt-2 border-top d-flex gap-2">
                                    <a href="{{ route('berita.edit', $berita) }}" class="btn btn-sm btn-outline-warning">Edit</a>

                                    @if (auth()->user()->isAdmin())
                                        <form action="{{ route('berita.destroy', $berita) }}" method="POST"
                                              onsubmit="return confirm('Yakin hapus berita ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Berita tidak ditemukan.</p>
            @endforelse
        </div>

        @if ($beritas->total() > 0)
            <p class="text-muted small">
                Menampilkan {{ $beritas->firstItem() }} - {{ $beritas->lastItem() }} dari {{ $beritas->total() }} hasil
            </p>
        @endif

        {{ $beritas->links() }}
    </div>
</div>
@endsection
