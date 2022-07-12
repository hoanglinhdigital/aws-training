@extends('layouts.create')

@section('form')
    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
        <legend class="w-auto">Loại công việc</legend>
        @include('components.flash-message')
        <form action="{{route('project-type.update',['id'=>$projectType->id])}}" method="POST">
            @csrf
            <div class="form-group-row mb-3">
                @include('components.input-text', [
                    'name' => 'project_type_code',
                    'label' => 'Mã',
                    'value' => $projectType->code,
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            <div class="form-group-row mb-3">
                @include('components.input-text', [
                    'name' => 'project_type_name',
                    'value' => $projectType->name,
                    'label' => 'Tên',
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            <div class="form-group-row mb-3">
                @include('components.input-number', [
                   'name' => 'project_type_deadline',
                   'label' => 'Hạn xử lý',
                   'value' => $projectType->deadline,
                   'inputClass' => 'form-control d-inline w-75',
                   'min' => 0
               ])
            </div>
            <div class="form-group-row mb-5">
                @include('components.select', [
                    'name' => 'project_type_common',
                    'label' => 'Thường xuyên',
                    'value' => $projectType->common,
                    'options' => [
                                        ['value' => 0, 'display' => 'Không'],
                                        ['value' => 1, 'display' => 'Có'],
                                    ],
                    'checked' => $projectType->common
                ]),
            </div>
            @include('components.buttons', [
                'buttons' => [
                    ['iconClass' => 'fas fa-save', 'value' => 'Lưu' ]
                ]
            ])
            @include('components.span-modal', [
                'value' => 'Xóa',

            ])
        </form>
        @include('components.warning-modal', [
            'href' => route('project-type.destroy',['id'=>$projectType->id]),
            'messages' => 'Bạn có chắc chắn xóa loại công việc này không?'
        ])
    </fieldset>
@endsection

@section('table')
    @include('components.table', [
        'fields' => [
            'code' => 'code',
            'name_project_type' => 'name',
            'edit' => 'pattern.modified'
           ],
        'items' => $projectsType,
        'edit_route' => 'project-type.edit'
    ])

    {{$projectsType->links()}}

@endsection,

