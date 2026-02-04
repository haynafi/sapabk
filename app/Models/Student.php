<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasUuids, BelongsToSchool;

    protected $fillable = [
        'classroom_id',
        'name',
        'nisn',
        'photo_path',
        'biodata',
        // school_id NOT fillable - set via BelongsToSchool trait
    ];

    protected $casts = [
        'biodata' => 'array',
    ];

    protected $appends = ['photo_url'];

    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->photo_path ? Storage::url($this->photo_path) : null,
        );
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function quizResponses(): HasMany
    {
        return $this->hasMany(QuizResponse::class);
    }
}
