<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    /**
     * Log an activity for PDP compliance
     */
    public static function log(
        string $action,
        string $subjectType,
        ?string $subjectId = null,
        ?string $subjectName = null,
        ?string $description = null,
        ?array $metadata = null
    ): AuditLog {
        $user = auth()->user();

        return AuditLog::create([
            'school_id' => $user?->school_id,
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'subject_name' => $subjectName,
            'description' => $description ?? self::generateDescription($action, $subjectType, $subjectName),
            'metadata' => array_merge($metadata ?? [], [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]),
        ]);
    }

    /**
     * Log viewing a model
     */
    public static function logView(Model $model, ?string $name = null): AuditLog
    {
        return self::log(
            action: 'viewed',
            subjectType: class_basename($model),
            subjectId: $model->getKey(),
            subjectName: $name ?? $model->name ?? null
        );
    }

    /**
     * Log creating a model
     */
    public static function logCreate(Model $model, ?string $name = null): AuditLog
    {
        return self::log(
            action: 'created',
            subjectType: class_basename($model),
            subjectId: $model->getKey(),
            subjectName: $name ?? $model->name ?? null
        );
    }

    /**
     * Log updating a model
     */
    public static function logUpdate(Model $model, ?string $name = null): AuditLog
    {
        return self::log(
            action: 'updated',
            subjectType: class_basename($model),
            subjectId: $model->getKey(),
            subjectName: $name ?? $model->name ?? null
        );
    }

    /**
     * Log deleting a model
     */
    public static function logDelete(Model $model, ?string $name = null): AuditLog
    {
        return self::log(
            action: 'deleted',
            subjectType: class_basename($model),
            subjectId: $model->getKey(),
            subjectName: $name ?? $model->name ?? null
        );
    }

    /**
     * Log student quiz submission (public action)
     */
    public static function logQuizSubmission(
        int $schoolId,
        string $studentId,
        string $studentName,
        string $classroomId,
        string $classroomName
    ): AuditLog {
        return AuditLog::create([
            'school_id' => $schoolId,
            'user_id' => null,
            'user_name' => $studentName,
            'action' => 'submitted',
            'subject_type' => 'QuizResponse',
            'subject_id' => $studentId,
            'subject_name' => $classroomName,
            'description' => "Student {$studentName} submitted quiz for classroom: {$classroomName}",
            'metadata' => [
                'classroom_id' => $classroomId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
        ]);
    }

    protected static function generateDescription(string $action, string $subjectType, ?string $subjectName): string
    {
        $name = $subjectName ? ": {$subjectName}" : '';
        return ucfirst($action) . " {$subjectType}{$name}";
    }
}
