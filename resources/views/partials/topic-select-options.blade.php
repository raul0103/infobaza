@props(['selected' => null, 'groups'])

@foreach($groups['groups'] as $parent)
    <optgroup label="{{ $parent->name }}">
        <option value="{{ $parent->id }}" @selected((string) old('topic_id', $selected) === (string) $parent->id)>
            {{ $parent->name }} (основная)
        </option>
        @foreach($parent->children as $child)
            <option value="{{ $child->id }}" @selected((string) old('topic_id', $selected) === (string) $child->id)>
                — {{ $child->name }}
            </option>
        @endforeach
    </optgroup>
@endforeach

@if($groups['standalone']->isNotEmpty())
    <optgroup label="Отдельные темы">
        @foreach($groups['standalone'] as $topic)
            <option value="{{ $topic->id }}" @selected((string) old('topic_id', $selected) === (string) $topic->id)>
                {{ $topic->name }}
            </option>
        @endforeach
    </optgroup>
@endif
