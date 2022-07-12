@extends('layouts.app')

@section('content')
    <div class="container">
        <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
            <legend class="w-auto">Thống kê time sheet</legend>

            <div class="row mb-4 ml-0">
            <form action="{{route('timesheet-statis.search')}}" class="w-100" method="POST">
                @csrf
                <div class="form-group-row mb-3">
                    @include('components.input-date', [
                        'name' => 'from_date',
                        'label' => 'Từ ngày',
                        'type' => 'date',
                    ])
                    @include('components.input-date', [
                        'name' => 'to_date',
                        'label' => 'Đến ngày',
                        'type' => 'date',
                    ])
                </div>
                <div class="form-group-row mb-3">
                    @include('components.select', [
                        'name' => 'department',
                        'label' => 'Phòng ban',
                        'type' => 'date',
                        'options' => $departments
                    ])
                    @include('components.select', [
                        'name' => 'object_handling',
                        'label' => 'Đối tượng xử lý',
                        'type' => 'date',
                        'options' => $staffs
                    ])
                </div>
                <div class="form-group-row mb-3">
                    @include('components.button-group', [
                         'buttons' => [
                             ['iconClass' => 'fas fa-search', 'value' => 'Tìm kiếm', 'action' => 'search']
                         ]
                     ])

                    <a href="{{route('timesheet-statis.list')}}"> <i class="fas fa-sync-alt"></i> Reset</a>
                </div>
            </form>
        </div>
        </fieldset>

    </div>
            @include('components.table', [
                    'fields' => [
                        'create_day' => 'created_at',
                        'object_handling' => 'staff_name',
                        'name_project' => 'job_name',
                        'from_date' => 'from_date',
                        'to_date' => 'to_date',
                        'content' => 'content',
                        'from_time' => 'from_time',
                        'to_time' => 'to_time',
                        'finish' => 'finish'
                       ],
                    'items' => $timeStatistics,
                    'edit_route' => 'skill.edit'
                ])

    {{$timeStatistics->links()}}

@endsection