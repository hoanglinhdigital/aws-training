<label for="{{$name}}" class= "{{$labelClass ?? 'col-sm-2 col-form-label p-0'}}">
    <span>{{ $label }}</span>
    @if (isset($required) && $required)
        <i class="fas fa-asterisk" style="width: .5em; color:red"></i>
    @endif
</label>
<div class="{{$textClass ?? 'col-sm-4 d-inline'}}">
    <input type="text" name="{{$name}}" id="{{$name}}" value="{{ $value ?? old($name) }}" class="{{$inputClass ?? 'form-control d-inline w-25'}}" @if (isset($readonly) && $readonly) {{ 'readonly' }} @endif>
</div>    
{!!showErrors($errors, $name)!!}
