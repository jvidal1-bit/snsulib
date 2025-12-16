<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookRequest extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'chapter',
        'purpose',
        'note',
        'needed_by',
        'status',
        'completed_file',
        'prepared_by',
        'expiration_at',
    ];

    protected $casts = [
        'needed_by'    => 'date',
        'expiration_at'=> 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
