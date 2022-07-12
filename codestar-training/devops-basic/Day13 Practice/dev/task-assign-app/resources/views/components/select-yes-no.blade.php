@isset($label)
    <label for="{{$name}}" class="{{$labelClass ?? 'col-sm-2 col-form-label p-0'}}">{{$label ?? ''}}</label>
@endisset
<div class="{{$parentClass ?? 'col-sm-4 d-inline'}}">
    <select name="{{$name}}" id="{{$name}}" class="{{$selectClass ?? 'custom-select w-25'}}">
    
        <option value="1" @if($checked == 1) {{'selected'}} @endif> Có </option>
        <option value="0" @if($checked == 0) {{'selected'}} @endif> Không </option>
    
    </select>
</div>