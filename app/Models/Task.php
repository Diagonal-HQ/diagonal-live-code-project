<?php

namespace App\Models;

use App\Traits\HasRules;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Task
 *
 * Represents a task in the application.
 * Tasks can be nested and associated with users.
 *
 * @property string $id
 * @property string|null $parent_id
 * @property string $name
 * @property Carbon|null $completed_at
 * @property string $user_id
 * @property Carbon|null $due_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $short_name
 * @property-read Task|null $parent
 * @property-read User $user
 *
 * @mixin IdeHelperTask
 */
class Task extends Model
{
    use HasRules, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'parent_id',
        'name',
        'completed_at',
        'user_id',
        'due_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'due_date' => 'date',
    ];

    /**
     * Get the parent task that this task belongs to.
     *
     * @return BelongsTo<Task, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Scope a query to only include tasks that are not completed.
     *
     * @param  Builder<Task>  $query
     */
    public function scopeTodo(Builder $query): void
    {
        $query->whereNull('completed_at');
    }

    /**
     * Get the user that owns the task.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the short name of the task (without any tags).
     *
     * @return string The short name of the task
     */
    public function getShortNameAttribute(): string
    {
        return explode('#', $this->name)[0];
    }

    /**
     * Set the name of the task, handling tag extraction and formatting.
     *
     * @param  string  $name  The name to set
     */
    public function setNameAttribute(string $name): void
    {
        $parts = explode('#', $name);
        $name = trim(array_shift($parts));

        $tags = collect($parts)
            ->map(fn ($tag) => explode(' ', trim($tag), 2)[0])
            ->filter()
            ->unique()
            ->map(fn ($tag) => "#$tag")
            ->join(' ');

        $this->attributes['name'] = trim("$name $tags");
    }
}
