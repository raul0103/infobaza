<?php

namespace App\Models;

use App\Services\SpacedRepetition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DictionaryEntry extends Model
{
    public const RELATION_SYNONYM = 'synonym';

    public const RELATION_ANTONYM = 'antonym';

    protected $fillable = [
        'dictionary_id', 'group_id', 'term', 'definition', 'example',
        'next_review_at', 'interval_days', 'review_count',
    ];

    protected function casts(): array
    {
        return ['next_review_at' => 'datetime'];
    }

    public function dictionary(): BelongsTo
    {
        return $this->belongsTo(Dictionary::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(DictionaryEntryGroup::class, 'group_id');
    }

    public function synonyms(): BelongsToMany
    {
        return $this->relatedEntries(self::RELATION_SYNONYM);
    }

    public function antonyms(): BelongsToMany
    {
        return $this->relatedEntries(self::RELATION_ANTONYM);
    }

    public function relatedEntries(string $type): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'dictionary_entry_relations',
            'entry_id',
            'related_entry_id'
        )->wherePivot('type', $type)->orderByRaw('LOWER(term)');
    }

    /**
     * @param  array<int|string>  $ids
     */
    public function syncRelations(string $type, array $ids): void
    {
        if (! in_array($type, [self::RELATION_SYNONYM, self::RELATION_ANTONYM], true)) {
            throw new \InvalidArgumentException('Unknown relation type: '.$type);
        }

        $newIds = collect($ids)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->reject(fn (int $id) => $id === (int) $this->id)
            ->values();

        DB::transaction(function () use ($type, $newIds) {
            $currentIds = DB::table('dictionary_entry_relations')
                ->where('entry_id', $this->id)
                ->where('type', $type)
                ->pluck('related_entry_id')
                ->map(fn ($id) => (int) $id);

            $toDetach = $currentIds->diff($newIds)->values();
            $toAttach = $newIds->diff($currentIds)->values();

            if ($toDetach->isNotEmpty()) {
                DB::table('dictionary_entry_relations')
                    ->where('type', $type)
                    ->where(function ($q) use ($toDetach) {
                        $q->where(function ($inner) use ($toDetach) {
                            $inner->where('entry_id', $this->id)
                                ->whereIn('related_entry_id', $toDetach);
                        })->orWhere(function ($inner) use ($toDetach) {
                            $inner->whereIn('entry_id', $toDetach)
                                ->where('related_entry_id', $this->id);
                        });
                    })
                    ->delete();
            }

            $rows = [];
            foreach ($toAttach as $relatedId) {
                $rows[] = [
                    'entry_id' => $this->id,
                    'related_entry_id' => $relatedId,
                    'type' => $type,
                ];
                $rows[] = [
                    'entry_id' => $relatedId,
                    'related_entry_id' => $this->id,
                    'type' => $type,
                ];
            }

            if ($rows !== []) {
                DB::table('dictionary_entry_relations')->insert($rows);
            }
        });
    }

    public function scopeDue(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('next_review_at')->orWhere('next_review_at', '<=', now());
        });
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $q) use ($like) {
            $q->whereRaw('LOWER(term) LIKE LOWER(?)', [$like])
                ->orWhereRaw('LOWER(definition) LIKE LOWER(?)', [$like])
                ->orWhereRaw('LOWER(COALESCE(example, "")) LIKE LOWER(?)', [$like]);
        });
    }

    public function recordReview(bool $known): void
    {
        SpacedRepetition::scheduleReview($this, $known);
    }

    /**
     * @return Collection<int, string>
     */
    public function synonymTerms(): Collection
    {
        return $this->relationLoaded('synonyms')
            ? $this->synonyms->pluck('term')
            : collect();
    }

    /**
     * @return Collection<int, string>
     */
    public function antonymTerms(): Collection
    {
        return $this->relationLoaded('antonyms')
            ? $this->antonyms->pluck('term')
            : collect();
    }
}
