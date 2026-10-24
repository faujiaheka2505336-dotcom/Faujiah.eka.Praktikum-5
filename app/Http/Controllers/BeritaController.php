<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        // with(): eager loading kategori & tags sekaligus, sehingga tidak ada
        // query berulang untuk setiap kartu berita (masalah N+1).
        $beritas = Berita::with(['kategori', 'tags'])
            ->filter($request->only('q', 'kategori', 'tag'))
            ->latest('published_at')
            ->paginate(6)
            ->withQueryString();

        return view('berita.index', [
            'beritas'   => $beritas,
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function create(): View
    {
        return view('berita.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('gambar')) {
            // tersimpan di storage/app/public/berita/xxxx.jpg
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $tags = Arr::pull($data, 'tags', []);

        // Dibuat lewat relasi user: user_id terisi otomatis dari akun yang login.
        $berita = $request->user()->beritas()->create($data);
        $berita->tags()->sync($tags);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil ditambahkan!');
    }

    public function show(Berita $berita): View
    {
        $berita->increment('dilihat');
        $berita->load(['kategori', 'tags', 'user']);

        // Sidebar "Berita Terkait": berita lain, terbaru dulu.
        $terkait = Berita::where('id', '!=', $berita->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('berita.show', compact('berita', 'terkait'));
    }

    public function edit(Berita $berita): View
    {
        return view('berita.edit', [
            'berita' => $berita->load('tags'),
        ] + $this->formOptions());
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama agar storage tidak menumpuk file yatim.
            $berita->deleteLocalImage();
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $tags = Arr::pull($data, 'tags', []);

        $berita->update($data);

        // sync(): id baru ditambahkan, id yang tidak dicentang dihapus dari tabel pivot.
        $berita->tags()->sync($tags);

        return redirect()->route('berita.show', $berita)
            ->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        $berita->deleteLocalImage();

        // Baris pivot ikut terhapus karena cascadeOnDelete pada migration.
        $berita->delete();

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dihapus!');
    }

    /** Pilihan kategori dan tag untuk form create/edit. */
    private function formOptions(): array
    {
        return [
            'kategoris' => Kategori::orderBy('nama')->get(),
            'tags'      => Tag::orderBy('nama')->get(),
        ];
    }

    /** Validasi + normalisasi input yang dipakai bersama oleh store() dan update(). */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'judul'        => 'required|max:255',
            'kategori_id'  => 'required|exists:kategoris,id',
            'isi'          => 'required|min:10',
            'published_at' => 'nullable|date',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // maks 2 MB
            'tags'         => 'nullable|array',
            'tags.*'       => 'exists:tags,id',
        ]);

        $data['published_at'] = $data['published_at'] ?? now();

        // Berkas diproses terpisah di store()/update(). Tanpa upload baru,
        // kolom gambar tidak boleh tertimpa null saat update.
        unset($data['gambar']);

        return $data;
    }
}
