@isset($label)
    <label for="{{$name}}" class="{{$labelClass ?? 'col-sm-2 col-form-label p-0'}}">{{$label ?? ''}}</label>
@endisset
<div class="{{$parentClass ?? 'col-sm-4 d-inline'}}">
    <select name="{{$name}}" id="{{$name}}" class="{{$selectClass ?? 'custom-select w-25'}}">
        
        @isset($defaultText)
            <option value="" selected disabled hidden>{{$defaultText}}</option>
        @endisset

        
        @foreach ($options as $option) 
            <option value="{{$option['value'] ?? $option['id'] ?? $option}}" @if(isset($checked) && (isset($option['id']) ? $checked == $option['id'] : $checked == $option['value'])) {{'selected'}} @endif> {{$option['display'] ?? $option['name'] ?? $option}} </option>
        @endforeach
        
    </select>
</div>
{!!showErrors($errors, $name)!!}

