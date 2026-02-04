<?php

namespace App\Policies;

use App\Models\QuizTemplate;
use App\Models\User;

class QuizTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, QuizTemplate $quizTemplate): bool
    {
        return $user->school_id === $quizTemplate->school_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, QuizTemplate $quizTemplate): bool
    {
        return $user->id === $quizTemplate->teacher_id;
    }

    public function delete(User $user, QuizTemplate $quizTemplate): bool
    {
        return $user->id === $quizTemplate->teacher_id;
    }
}
