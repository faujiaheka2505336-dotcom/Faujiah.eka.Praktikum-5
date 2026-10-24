<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Berita extends Model
{
    public const PLACEHOLDER = 'https://placehold.co/800x450/0f3460/ffffff?text=Berita+Desa';

    // user_id sengaja tidak masuk $fillable: selalu diisi lewat $user->beritas()->create().
    protected $fillable = [
        'judul', 'kategori_id', 'gambar', 'isi', 'dilihat', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /** Berita milik satu kategori (pemegang foreign key -> belongsTo). */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /** Berita ditulis oleh satu user (pemegang foreign key -> belongsTo). */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Berita punya banyak tag (Many to Many lewat tabel berita_tag). */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Berita::filter($request->only('q', 'kategori', 'tag'))
     * 'kategori' dan 'tag' berisi ID. Semua filter opsional; yang kosong diabaikan oleh when().
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, fn ($q, $kata) => $q->where('judul', 'like', "%{$kata}%"))
            ->when($filters['kategori'] ?? null, fn ($q, $id) => $q->where('kategori_id', $id))
            // whereHas lewat relasi: berfungsi di semua database (bukan hanya MySQL)
            ->when($filters['tag'] ?? null, fn ($q, $id) => $q->whereHas(
                'tags', fn ($t) => $t->where('tags.id', $id)
            ));
    }

    /** Gambar bisa berupa URL penuh (seeder) atau path upload di disk "public". */
    public function getGambarUrlAttribute(): string
    {
        if (! $this->gambar) {
            return self::PLACEHOLDER;
        }

        if (Str::startsWith($this->gambar, ['http://', 'https://'])) {
            return $this->gambar;
        }

        return Storage::disk('public')->exists($this->gambar)
            ? asset('storage/' . $this->gambar)
            : self::PLACEHOLDER;
    }

    /** Hapus file gambar hanya bila hasil upload lokal (bukan URL eksternal). */
    public function deleteLocalImage(): void
    {
        if ($this->gambar && ! Str::startsWith($this->gambar, ['http://', 'https://'])) {
            Storage::disk('public')->delete($this->gambar);
        }
    }
}
