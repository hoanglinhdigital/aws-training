<label for="{{$name}}" class= "{{$labelClass ?? 'col-sm-2 col-form-label p-0'}}" style="margin-right: 1rem">{{$label}}</label>
<input list="brow" name="$name" class="{{$inputClass ?? 'form-control d-inline w-25'}}">

<datalist id="brow">
    @foreach ($options as $option)
        <option value="{{$option['id'] ?? $option}}" >{{$option['name'] ?? $option}}</option>
    @endforeach
</datalist>