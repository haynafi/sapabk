<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use BelongsToSchool;

    public $timestamps = false;  // Only created_at, no updated_at

    protected $fillable = [
        'school_id',  // Exception: Explicitly set for public actions (student submissions)
        'user_id',
        'user_name',
        'action',
        'subject_type',
        'subject_id',
        'subject_name',
        'description',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->created_at = now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
