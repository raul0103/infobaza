@php
    $percent = $plan->progressPercent();
    $meta = collect([
        $percent !== null ? $percent.'%' : null,
        $plan->steps_count > 0 ? $plan->steps_done_count.'/'.$plan->steps_count.' шагов' : null,
    ])->filter()->implode(' · ');
@endphp
<x-list-row-card
    :href="route('plans.show', $plan)"
    :title="$plan->title"
    :subtitle="$meta ?: null"
>
    @include('partials.item-actions', [
        'edit' => route('plans.edit', $plan),
        'destroy' => route('plans.destroy', $plan),
    ])
</x-list-row-card>
