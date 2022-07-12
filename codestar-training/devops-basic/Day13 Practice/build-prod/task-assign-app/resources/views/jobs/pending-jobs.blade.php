@extends('jobs.index', [
    'left_title' => 'Công việc đang chờ nhận',
    'right_title' => 'Công việc chưa có người nhận',
    'type' => $type
])


@section('left-tab-table')
    @include('components.dynamic-table', [
        'id' => 'left-table',
        'cols' => [                               
            'Mã dự án' => 'project_code',         
            'Tên công việc' => 'name',                 
            'Người giao' => 'assigner',   
            'Hạn xử lý' => 'deadline',
            'KL giao' => 'assign_amount',
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
            'Hạn xử lý' => 'deadline',
            'KL giao' => 'assign_amount',
            'checkbox' => 'job_ids[]'
        ],
        'rows' => $rightTableJobs,                       
        'min_row' => 4,                          
    ])
@endsection



