@extends('layouts.app')

@section('content')

    <div class="container">
        @include('components.table', [
            'cols' => ['Ngày sửa đổi', 'Tên trường', 'Giá trị cũ', 'Giá trị thay đổi', 'Ghi chú sửa đổi'],
            'rows' => [
                ['18/6/2021', 'a', 'val1', 'val2', 'note1'],
                ['19/6/2021', 'b', 'val1', 'val2', 'note2'],
                ['20/6/2021', 'c', 'val1', 'val2', 'note3'],
                ['21/6/2021', 'd', 'val1', 'val2', 'note4'],
            ]
            
        ])
    </div>
    
@endsection