<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizTemplate extends Model
{
    use BelongsToSchool;

    protected $fillable = [
        'teacher_id',
        'title',
        'questions',
        // school_id NOT fillable - set via BelongsToSchool trait
    ];

    protected $casts = [
        'questions' => 'array',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    public function quizResponses(): HasMany
    {
        return $this->hasMany(QuizResponse::class);
    }
}
