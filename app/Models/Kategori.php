<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $fillable = ['nama'];

    /** Satu kategori memiliki banyak berita (One to Many). */
    public function beritas(): HasMany
    {
        return $this->hasMany(Berita::class);
    }
}
