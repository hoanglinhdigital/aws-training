@extends('jobs.index', [
    'left_title' => 'Công việc đã giao xử lý',
    'right_title' => 'Công việc chuyển tiếp, bổ sung',
    'type' => $type
])


@section('left-tab-table')
    @include('components.dynamic-table', [
        'id' => 'left-table',
        'cols' => [                               
            'Mã dự án' => 'project_code',        
            'Tên công việc' => 'name',               
            'Người giao' => 'assigner',   
            'Khác' => 'others',
            'Hạn xử lý' => 'deadline',
            'KL giao' => 'assign_amount',
            'Số ngày còn lại' => 'remaining',
            'Trạng thái' => 'status',
            'Đánh giá' => 'evaluation',
            'checkbox' => 'job_ids[]'
        ],
        'rows' => $leftTableJobs,                      
        'min_row' => 4,                             
    ])
@endsection


@section('right-tab-table')
    @include('components.dynamic-table', [
        'id' => 'right-table',
        'cols' => [                              
            'Mã dự án' => 'project_code',        
            'Tên công việc' => 'name',               
            'Người giao' => 'assigner',   
            'Khác' => 'others',
            'Hạn xử lý' => 'deadline',
            'KL giao' => 'assign_amount',
            'Số ngày còn lại' => 'remaining',
            'Trạng thái' => 'status',
            'Đánh giá' => 'evaluation',
            'checkbox' => 'job_ids[]'
        ],
        'rows' => $rightTableJobs,                       
        'min_row' => 4,                          
    ])
@endsection



