<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Archive extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'document_number',
        'title',
        'file_path',
        'file_name_original',
        'file_mime_type',
        'file_size',
        'category_id',
        'division_id',
        'location_id',
        'document_date',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'document_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $archive) {
            if (empty($archive->uuid)) {
                $archive->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function borrowingLogs()
    {
        return $this->hasMany(BorrowingLog::class);
    }
}
