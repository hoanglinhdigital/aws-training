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
                            'type' => 'date',
                            'options' => $projects,
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

    </div>
    
    <form action="{{ route('jobs.detailAction') }}" method="POST">
        @csrf
        
        @include('components.dynamic-table', [
            'cols' => [
                'Tên công việc' => 'name',
                'Đối tượng xử lý' => 'name_oject',
                'SĐT' => 'phone',
                'Email' => 'email',
                'Hạn xử lý' => 'deadline',
                'Khối lượng giao    ' => 'delivery_volume',
                'Khối lượng timesheet' => 'timesheet_volume',
                '% hoàn thành' => 'finish',
                'checkbox' => 'job_ids[]'  
            ],
            'rows' => $jobAssigns,
        ])
    
        <div class="text-center">
            <button class="ml-3 btn btn-primary" name="action" value="detail">Xem chi tiết</button>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $('thead input:checkbox').hide();
            $('.pagination').css('justify-content', 'center');

            $('tbody input:checkbox').change(function() {
                if (this.checked) {
                    $('tbody input:checkbox').not(this).prop('disabled', true);
                }
                else {
                    $('tbody input:checkbox').prop('disabled', false);
                }
            });
        });
    </script>


@endsection