<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    /**
     * OWASP Protezione Mass Assignment: 
     * Definiamo esplicitamente solo i campi che possono essere scritti massivamente.
     */
    protected $fillable = [
        'title',
        'author',
        'description',
        'isbn',
        'published_at'
    ];

    /**
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}