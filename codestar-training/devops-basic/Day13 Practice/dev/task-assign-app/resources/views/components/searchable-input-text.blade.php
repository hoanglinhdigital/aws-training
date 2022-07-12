<label for="{{$name}}" class= "{{$labelClass ?? 'col-sm-2 col-form-label p-0'}}">
    {{$label}} 
</label>
<div class="{{$parentClass ?? 'col-sm-4 d-inline'}}">

    <select name="{{$name}}" id="{{$name}}" class="{{$selectClass ?? 'selectpicker w-25'}}" data-live-search="true" multiple data-max-options="1">
        @foreach ($options ?? [] as $option)
            <option data-hidden="{{ isset($hiddenField) ? $option[$hiddenField] ?? '' : '' }}"  value="{{$option['id'] ?? $option}}" 
                @if(isset($checked) && $checked == $option['id']) {{ 'checked' }} @endif
            >
                {{$option[$displayField ?? 'name'] ?? $option}}
            </option>
        @endforeach
    </select>
</div>  

