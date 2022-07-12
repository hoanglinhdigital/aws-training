<div class="mb-3 row">
    <label for="{{ $name }}" class="col-sm-2 col-form-label">{{ __("title.$name") }}</label>
    <div class="col-sm-6">
        <select name="course" class="form-select course-select">
            @foreach ($options as $option)
                <option value="{{ ($valueField ?? false) ? $option->$valueField : $option }}" {{ ($select ?? '') == ($valueField ? $option->$valueField : $option) ? 'selected': '' }}>
                    {{ $displayField ? $option->$displayField : $option }}
                </option>
            @endforeach
        </select>
    </div>
</div>
