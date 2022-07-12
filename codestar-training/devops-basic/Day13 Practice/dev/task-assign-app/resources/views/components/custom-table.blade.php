@if (isset($id))
    <table class=" table table-hover table-light table-stripped dynamic-table" id="{{$id}}">
@else
    <table class=" table table-hover table-light table-stripped dynamic-table">
@endif
    <thead>
        <tr>
            @foreach ($cols ?? [] as $key => $value)
                @if ($key === 'checkbox')
                    <th><input type="checkbox" ></th>
                @else
                    <th scope="col" data-value="{{$value}}">{{ $key }}</th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rows ?? [] as $item)
            <tr class="data-row" id="{{isset($idField) ? $item[$idField] : $item['id']}}">
                @foreach ($cols ?? [] as $key => $value)
                    @if ($value === 'pattern.modified')
                    @elseif ($key === 'checkbox')
                        <td><input name="{{ $value }}" type="checkbox"></td>
                    @else
                        <td class="{{ $value }}">{{ isset($$value) && array_key_exists($item->$value, $$value) ? $$value[$item->$value] : $item->$value }}
                        </td>
                    @endif

                @endforeach
            </tr>
        @endforeach

        

        {{-- Inject blank row --}}
        @isset($min_row)
            @for ($i = 0; $i < $min_row - count($rows); $i++)
                <tr>
                    @foreach ($cols ?? [] as $key => $value)
                        <td>
                        </td>
                    @endforeach
                </tr>
            @endfor
        @endisset


    </tbody>
</table>

@isset($pagination)
    {{ $rows->onEachSide(2)->links('pagination::bootstrap-4') }}
@endisset



