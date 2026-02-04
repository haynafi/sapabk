<?php

namespace App\Traits;

use App\Models\School;
use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToSchool
{
    protected static function bootBelongsToSchool(): void
    {
        static::addGlobalScope(new SchoolScope);

        // Auto-assign school_id on creation - ONLY way to set school_id
        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->school_id) {
                $model->school_id = auth()->user()->school_id;
            }
        });
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
