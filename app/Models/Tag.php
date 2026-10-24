<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['nama'];

    /** Satu tag dipakai banyak berita (Many to Many lewat tabel berita_tag). */
    public function beritas(): BelongsToMany
    {
        return $this->belongsToMany(Berita::class);
    }
}
