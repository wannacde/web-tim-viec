<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Job extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'location_id', 'title', 'slug',
        'description', 'requirements', 'benefits', 'salary_min', 'salary_max',
        'salary_type', 'work_type', 'work_schedule', 'experience_level',
        'positions', 'status', 'deadline', 'is_featured', 'is_urgent'
    ];

    protected $casts = [
        'work_schedule' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'deadline' => 'date',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_jobs');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}