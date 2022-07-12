@extends('jobs.index', [
    'left_title' => 'Công việc trực tiếp xử lý',
    'right_title' => 'Công việc liên quan',
    'type' => $type
])


@section('left-tab-table')
    @include('components.dynamic-table', [
        'id' => 'left-table',
        'cols' => [                               
            'Mã dự án' => 'project_code',         
            'Tên công việc' => 'name',                 
            'Người giao' => 'assigner',   
            'Hình thức xử lý' => 'process_method',
            'Hạn xử lý' => 'deadline',
            'KL giao' => 'assign_amount',
            'KL timesheet' => 'timesheet_amount',
            '% hoàn thành' => 'finished_percent',
            'Số ngày còn lại' => 'remaining',
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
            'Hình thức xử lý' => 'process_method',
            'Người chuyển tiếp' => 'forward',
            'Hạn xử lý' => 'deadline',
            'KL giao' => 'assign_amount',
            'KL timesheet' => 'timesheet_amount',
            '% hoàn thành' => 'finished_percent',
            'Số ngày còn lại' => 'remaining',
            'checkbox' => 'job_ids[]'
        ],
        'rows' => $rightTableJobs,                       
        'min_row' => 4,                          
    ])
@endsection



