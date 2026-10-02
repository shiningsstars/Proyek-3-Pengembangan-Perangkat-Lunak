<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    public const STATUS_DRAFT = 'Draft';
    public const STATUS_PUBLISHED = 'Published';
    public const STATUS_COMPLETED = 'Completed';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PUBLISHED,
        self::STATUS_COMPLETED,
    ];

    protected $fillable = [
        'category_id',
        'code',
        'title',
        'description',
        'start_at',
        'end_at',
        'capacity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }
        
        $escaped = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $term);
        $like = "%{$escaped}%";

        return $query->where(fn (Builder $w) => $w
            ->whereRaw("code LIKE ? ESCAPE '!'", [$like])
            ->orWhereRaw("title LIKE ? ESCAPE '!'", [$like]));
    }

    public function scopeOfCategory(Builder $query, ?int $categoryId): Builder
    {
        return $query->when($categoryId, fn (Builder $q) => $q->where('category_id', $categoryId));
    }

    public function scopeOfStatus(Builder $query, ?string $status): Builder
    {
        return $query->when(
            in_array($status, self::STATUSES, true),
            fn (Builder $q) => $q->where('status', $status)
        );
    }

    public function scopeSortByStart(Builder $query, ?string $sort): Builder
    {
        $direction = $sort === 'oldest' ? 'asc' : 'desc';

        return $query->orderBy('start_at', $direction)->orderBy('id', $direction);
    }
}