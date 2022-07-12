@extends('layouts.create')

@section('form')
    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
        <legend class="w-auto">Loại công việc</legend>
        @include('components.flash-message')
        <form action="{{route('project-type.store')}}" method="POST">
            @csrf
            <div class="form-group-row mb-3">
                @include('components.input-text', [
                    'name' => 'project_type_code',
                    'label' => 'Mã code',
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            <div class="form-group-row mb-3">
                @include('components.input-text', [
                    'name' => 'project_type_name',
                    'label' => 'Loại công việc',
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            <div class="form-group-row mb-3">
                @include('components.input-number', [
                   'name' => 'project_type_deadline',
                   'label' => 'Hạn xử lý',
                   'inputClass' => 'form-control d-inline w-75'
               ])
            </div>
            <div class="form-group-row mb-5">
                @include('components.select', [
                    'name' => 'project_type_common',
                    'label' => 'Thường xuyên',
                     'options' => [
                                        ['value' => 0, 'display' => 'Không'],
                                        ['value' => 1, 'display' => 'Có'],

                                    ],
                     'checked' => 0
                ])
            </div>

            @include('components.button-group', [
                'buttons' => [
                    ['iconClass' => 'fas fa-save', 'value' => 'Lưu' ],
                ]
            ])
            <a href="" class="btn btn-light"> <i class="fas fa-trash"></i> Xóa </a>
        </form>
    </fieldset>
@endsection

@section('table')
    @include('components.table', [
        'fields' => [
            'code' => 'code',
            'name_project_type' => 'name',
            'name_deadline' => 'deadline',
            'edit' => 'pattern.modified'
           ],
        'items' => $projectsType,
        'edit_route' => 'project-type.edit'
    ])

{{$projectsType->links()}}

@endsection,

