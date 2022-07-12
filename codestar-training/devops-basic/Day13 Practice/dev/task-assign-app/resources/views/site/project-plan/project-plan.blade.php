@extends('layouts.app')

@section('content')
    <div class="container">
        <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
            <legend class="w-auto">Kế hoạch dự án</legend>

            <div class="row mb-4 ml-0">
                <form action="{{route('project-plan.search')}}" class="w-100" method="POST">
                    @csrf
                    <div class="form-group-row mb-3">                        
                        @include('components.select', [
                            'name' => 'project_id',
                            'label' => 'Dự án',
                            'options' => $projects,
                            'checked' => isset($curProject) ? $curProject->id : null
                        ])
                        @include('components.button-group', [
                             'buttons' => [
                                 ['class' => 'btn btn-primary', 'iconClass' => 'fas fa-search', 'value' => 'Tìm kiếm', 'action' => 'search']
                             ]
                         ])
                        <a href="{{route('project-plan.list')}}" class="ml-3"> <i class="fas fa-sync-alt"></i> Reset</a>
                    </div>

                </form>
            </div>
        </fieldset>

        <form action="{{ route('jobs.detailAction') }}" method="POST">
            @csrf

    
            @include('components.dynamic-table', [
                'cols' => [
                    'Tên công việc' => 'name',
                    'Đối tượng xử lý' => 'assignees',
                    'SĐT' => 'main_assignee_phone',
                    'Email' => 'main_assignee_email',
                    'Hạn xử lý' => 'deadline',
                    'Khối lượng giao' => 'assign_amount',
                    'Khối lượng timesheet' => 'timesheet_amount',
                    '% hoàn thành' => 'finished_percent',
                    'checkbox' => 'job_ids[]'  
                ],
                'rows' => $jobs ?? [],
                'pagination' => true
            ])
        
            <div class="text-center">
                <button class="ml-3 btn btn-primary" name="action" value="detail">
                    <i class="fas fa-info-circle"></i>
                    <span>Xem chi tiết</span> 
                </button>
            </div>
        </form>

    </div>



    <script>
        $(document).ready(function() {
            $('thead input:checkbox').hide();
            $('.pagination').css('justify-content', 'center');
            $('button[value="detail"]').prop('disabled', true);

            $('tbody input:checkbox').change(function() {
                if (this.checked) {
                    $('tbody input:checkbox').not(this).prop('disabled', true);
                    $('button[value="detail"]').prop('disabled', false);
                }
                else {
                    $('tbody input:checkbox').prop('disabled', false);
                    $('button[value="detail"]').prop('disabled', true);
                }
            });

        
        })
    </script>


@endsection