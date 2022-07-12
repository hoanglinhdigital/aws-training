<div class="{{$parentClass ?? 'btn-group offset-4'}}" role="group">
    @foreach ($buttons as $btn)
        @if (isset($btn['id']))
            <button type="{{$btn['type'] ?? 'submit'}}" id="{{$btn['id']}}" class="{{$btn['class'] ?? 'btn btn-light mr-3'}}" name="{{$btn['name'] ?? 'action'}}" value="{{$btn['action'] ?? null}}">
                <i class="{{$btn['iconClass'] }}"></i>
                <span>{{$btn['value']}}</span>
            </button>
        @else
            <button type="{{$btn['type'] ?? 'submit'}}" class="{{$btn['class'] ?? 'btn btn-light mr-3'}}" name="{{$btn['name'] ?? 'action'}}" value="{{$btn['action'] ?? null}}">
                <i class="{{$btn['iconClass'] }}"></i>
                <span>{{$btn['value']}}</span>
            </button>
        @endif

    @endforeach
</div>