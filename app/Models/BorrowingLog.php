<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingLog extends Model
{
    protected $fillable = [
        'archive_id',
        'borrower_name',
        'borrower_division',
        'borrowed_at',
        'returned_at',
        'notes',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }
}
