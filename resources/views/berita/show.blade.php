@extends('layouts.app')

@section('title', $berita->judul)

@section('content')
    <a href="{{ route('berita.index') }}" class="text-decoration-none">← Kembali ke Berita</a>

    <div class="row g-4 mt-1">
        {{-- Artikel --}}
        <article class="col-lg-8">
            <div class="panel">
                <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="artikel-img"
                     onerror="this.onerror=null;this.src='{{ \App\Models\Berita::PLACEHOLDER }}'">

                <div class="p-4">
                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted small mb-2">
                        <span class="badge badge-kategori">{{ $berita->kategori->nama }}</span>
                        <span><i class="bi bi-calendar3"></i> {{ $berita->published_at?->translatedFormat('l, d F Y') }}</span>
                        <span><i class="bi bi-person-fill"></i> {{ $berita->user->name }}</span>
                        <span><i class="bi bi-eye-fill"></i> {{ number_format($berita->dilihat) }} dibaca</span>
                    </div>

                    <h2 class="fw-bold mb-3">{{ $berita->judul }}</h2>

                    <div class="artikel-isi">{!! nl2br(e($berita->isi)) !!}</div>

                    @if ($berita->tags->isNotEmpty())
                        <div class="mt-4">
                            @foreach ($berita->tags as $tag)
                                <a href="{{ route('berita.index', ['tag' => $tag->id]) }}" class="tag-pill">#{{ $tag->nama }}</a>
                            @endforeach
                        </div>
                    @endif

                    @auth
                        <div class="d-flex gap-2 pt-3 mt-3 border-top">
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
        </article>

        {{-- Berita Terkait --}}
        <aside class="col-lg-4">
            <div class="panel">
                <div class="panel-head"><i class="bi bi-newspaper me-1"></i> Berita Terkait</div>
                <div class="px-3">
                    @forelse ($terkait as $item)
                        <a href="{{ route('berita.show', $item) }}" class="terkait-item">
                            <img src="{{ $item->gambar_url }}" alt="" loading="lazy"
                                 onerror="this.onerror=null;this.src='{{ \App\Models\Berita::PLACEHOLDER }}'">
                            <div>
                                <div class="t">{{ Str::limit($item->judul, 70) }}</div>
                                <small class="text-muted"><i class="bi bi-calendar3"></i> {{ $item->published_at?->translatedFormat('d M Y') }}</small>
                            </div>
                        </a>
                    @empty
                        <p class="text-muted small py-3 mb-0">Belum ada berita lain.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
@endsection
