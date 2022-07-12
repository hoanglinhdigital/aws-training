@extends('layouts.app')

@section('content')
    <div class="container">
        <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
            <legend class="w-auto">Manday dự phòng</legend>
            <div class="row mb-4 ml-0">
                <form action="{{route('backup-manday.search')}}" class="w-100" method="POST">
                    @csrf
                    <div class="form-group-row mb-3">
                        @include('components.input-date', [
                            'name' => 'from_date',
                            'label' => 'Từ ngày',
                            'type' => 'date',
                            'value' => app('request')->input('from_date')
                        ])
                        <br>
                        @include('components.input-date', [
                            'name' => 'to_date',
                            'label' => 'Đến ngày',
                            'type' => 'date',
                            'value' => app('request')->input('to_date')
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
                            'name' => 'project',
                            'label' => 'Dự án',
                            'type' => 'date',
                            'options' => $projects
                        ])
                    </div>
                    <div class="form-group-row mb-3">
                        @include('components.button-group', [
                             'buttons' => [
                                 ['iconClass' => 'fas fa-search', 'value' => 'Tìm kiếm', 'action' => 'search']
                             ]
                         ])
                        <a href="{{route('backup-manday.list')}}"> <i class="fas fa-sync-alt"></i> Reset</a>
                    </div>
                </form>
            </div>
        </fieldset>
        @include('components.table', [
                    'fields' => [
                        'code' => 'code',
                        'name_project' => 'name_project',
                        'total_manday_lsx' => 'total_manday_lsx',
                        'total_manday_assign' => 'total_manday_assign',
                        'total_manday_reserve' => 'total_manday_reserve',
                       ],
                    'items' => $jobs,
                    'edit_route' => 'skill.edit'
                ])
    </div>

@endsection