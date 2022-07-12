<label for="{{$name}}" class= "{{$labelClass ?? 'col-sm-2 col-form-label p-0'}}">{{$label}}</label>
<div class="{{$textClass ?? 'col-sm-4 d-inline'}}">
    @if (isset($readonly) && $readonly)
    
        <textarea type="text" name="{{$name}}" id="{{$name}}" readonly class="form-control d-inline w-75">{{ $value ?? old($name) }}</textarea>

    @else
        
        <textarea type="text" name="{{$name}}" id="{{$name}}" class="form-control d-inline w-75">{{ $value ?? old($name) }}</textarea>

    @endif
    <br>{!!showErrors($errors, $name)!!}

</div>
