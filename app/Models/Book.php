<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'year_published',
        'isbn',
        'total_pages',
        'category_id',
        'status',
        'description',
        'table_of_contents',
        'cover_path'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(BookRequest::class);
    }
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_path) {
            return asset('storage/' . $this->cover_path);
        }

        return asset('assets/images/laravel-book.png'); // or some default cover
    }
}
