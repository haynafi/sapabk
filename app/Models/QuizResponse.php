<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResponse extends Model
{
    protected $fillable = [
        'student_id',
        'quiz_template_id',
        'classroom_id',
        'answers',
    ];

    protected $casts = [
        'answers' => 'encrypted:array',  // PDP COMPLIANCE
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function quizTemplate(): BelongsTo
    {
        return $this->belongsTo(QuizTemplate::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
