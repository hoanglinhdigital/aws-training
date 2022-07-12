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
                @elseif ($value === 'pattern.tick')
                    <th></th>
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
                    @if ($value === 'pattern.tick')
                        <td class="tick" style="max-width: 5px"> <i class="fas fa-check" style="display: none"></i> </td>
                    @elseif ($key === 'checkbox')
                        <td><input name="{{ $value }}" type="checkbox" value="{{$item['id']}}"></td>
                    @else
                        
                        <td class="{{ $value }}">{{ $item->$value }}</td>

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
@if (isset($pagination) && count($rows) > 0)
    {{ $rows->links('pagination::bootstrap-4') }}
@endif





