@extends('jobs.index', [
    'all' => true
])




@section('table')


    @include('components.dynamic-table', [
        'id' => 'all-jobs-table',
        'cols' => [                               
            'Mã dự án' => 'project_code',         
            'Tên công việc' => 'name',                 
            'Người giao' => 'assigner',
            'Chủ trì' => 'main_assignee',
            'Khác' => 'others',   
            'Hạn xử lý' => 'deadline',
            'KL giao' => 'assign_amount',
            'Ngày còn lại' => 'remaining',
            'Trạng thái' => 'status',
            'Đánh giá' => 'evaluation',
            'checkbox' => 'job_ids[]'
        ],
        'rows' => $jobs ?? [],                                  
    ])
@endsection

@section('custom-script')
    <script>
        $('tbody input:checkbox').change(function() {
            if (this.checked) {
                const jobId = $(this).closest('tr').attr('id');
                $(this).val(jobId);
                $('button[value="detail"]').prop('disabled', false);
            }
            else {
                $('thead input:checkbox').prop('checked', false);
                $(this).removeAttr('value');

                if ($('tbody input:checkbox:checked').length === 0) {
                    $('button[value="detail"]').prop('disabled', true);
                }
            }
        })
    </script>
@endsection




