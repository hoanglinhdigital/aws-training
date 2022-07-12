@extends('layouts.create')

@section('form')
    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
        <legend class="w-auto">Dự án</legend>
        @include('components.flash-message')
        <form action="{{route('project.store')}}" method="POST">
            @csrf
            <div class="form-group-row mb-3">
                @include('components.input-text', [
                    'name' => 'project_code',
                    'label' => 'Mã code',
                    'inputClass' => 'form-control d-inline w-75',
                    'value' => old('project_code')
                ])
            </div>
            <div class="form-group-row mb-5">
                @include('components.input-text', [
                    'name' => 'project_name',
                    'label' => 'Tên dự án',
                    'inputClass' => 'form-control d-inline w-75',
                    'value' => old('project_name')
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
            'name_project' => 'name',
            'edit' => 'pattern.modified'
           ],
        'items' => $projects,
        'edit_route' => 'project.edit'
    ])

    {{$projects->links()}}

@endsection






