@extends('layouts.app')

@section('content')

    <div class="container">
        @include('components.table', [
            'cols' => ['Người giao việc', 'Người đánh giá', 'Đối tượng xử lý', 'Hình thức xử lý', 'Lịch sử chuyển tiếp', 'Trạng thái'],
            'rows' => [],
            
        ])
    </div>
    
@endsection