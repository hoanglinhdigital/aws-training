<div class="{{$parentClass ?? 'btn-group offset-4'}}" role="group">
    @foreach ($buttons as $btn)
        <button type="submit" class="{{$btn['class'] ?? 'btn btn-light'}}" name="" value="" >
            <i class="{{$btn['iconClass'] }}"></i>
            <span>{{$btn['value']}}</span>
        </button>
    @endforeach
</div>