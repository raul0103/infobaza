<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Topic extends Model
{
    protected $fillable = ['name', 'slug', 'color', 'description', 'parent_id'];

    protected static function booted(): void
    {
        static::saving(function (Topic $topic) {
            if (empty($topic->slug)) {
                $topic->slug = Str::slug($topic->name);
            }

            if ($topic->parent_id) {
                $topic->color = null;
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Topic::class, 'parent_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    public function isChild(): bool
    {
        return $this->parent_id !== null;
    }

    /** Цвет только у основных тем; у подтем — null */
    public function markColor(): ?string
    {
        return $this->isChild() ? null : ($this->color ?: '#2563eb');
    }

    public function isParent(): bool
    {
        return $this->relationLoaded('children')
            ? $this->children->isNotEmpty()
            : $this->children()->exists();
    }

    /** @return array{groups: Collection<int, Topic>, standalone: Collection<int, Topic>} */
    public static function grouped(): array
    {
        $topics = static::withCount(['notes', 'quotes'])
            ->with(['children' => fn ($q) => $q->withCount(['notes', 'quotes'])->orderBy('name')])
            ->orderBy('name')
            ->get();

        $roots = $topics->whereNull('parent_id')->values();

        return [
            'groups' => $roots->filter(fn (Topic $t) => $t->children->isNotEmpty())->values(),
            'standalone' => $roots->filter(fn (Topic $t) => $t->children->isEmpty())->values(),
        ];
    }
}
