<label for="{{$name}}" class="{{$labelClass ?? 'col-sm-2 col-form-label p-0'}}">{{$label ?? ''}}</label>
<div class="{{$parentClass ?? 'col-sm-4 d-inline'}}">
    <select name="{{$name}}" id="{{$name}}" class="{{$selectClass ?? 'selectpicker w-25'}}" data-live-search="true" multiple>
        @foreach ($options ?? [] as $option)
            <option value="{{$option['id'] ?? $option}}">{{$option['name'] ?? $option}}</option>
        @endforeach
    </select>
</div>

  