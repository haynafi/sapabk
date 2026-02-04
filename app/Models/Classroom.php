<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasUuids, BelongsToSchool;

    protected $fillable = [
        'teacher_id',
        'name',
        'unit_code',
        'quiz_template_id',
        // school_id NOT fillable - set via BelongsToSchool trait
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function quizTemplate(): BelongsTo
    {
        return $this->belongsTo(QuizTemplate::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function quizResponses(): HasMany
    {
        return $this->hasMany(QuizResponse::class);
    }
}
