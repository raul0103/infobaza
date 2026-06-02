@props(['name', 'label', 'checked' => false])

<div class="form-group">
    <label class="flex items-center gap-3 cursor-pointer">
        <input
            type="checkbox"
            name="{{ $name }}"
            value="1"
            @checked(old($name, $checked))
            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
        >
        <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
    </label>
</div>
