<ul class="list-unstyled components">
    @foreach ($options as $option)
        @isset($option['parentText'])
            <li>
                <a data-toggle="collapse" href="#{{ $option['href'] }}" role="button" aria-expanded="false" aria-controls="{{ $option['href'] }}">
                    {{ $option['parentText'] }}
                </a>

                <ul id="{{ $option['href'] }}" class="list-unstyled components collapse">
                    @foreach ($option['children'] ?? [] as $child)
                        <li>
                            <a href="{{$child['link'] ?? '#'}}">{{$child['value'] ?? $child}}</a>
                        </li>
                    @endforeach
                </ul>
            </li>

        @else 
            <li>
                <a href="{{$option['link'] ?? '#'}}">{{$option['value'] ?? $option}}</a>
            </li>
        @endisset

    @endforeach

</ul>