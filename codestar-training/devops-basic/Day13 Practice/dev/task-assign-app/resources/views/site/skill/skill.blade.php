@extends('layouts.create')

@section('form')
    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
        <legend class="w-auto">Kỹ năng</legend>
        @include('components.flash-message')

        <form action="{{route('skill.store')}}" method="POST">
            @csrf
            <div class="form-group-row mb-3">
                @include('components.input-text', [
                    'name' => 'skill_code',
                    'label' => 'Mã code',
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            <div class="form-group-row mb-5">
                @include('components.input-text', [
                    'name' => 'skill_name',
                    'label' => 'Kỹ năng',
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            @include('components.buttons', [
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
            'name_skill' => 'name',
            'edit' => 'pattern.modified'
           ],
        'items' => $skills,
        'edit_route' => 'skill.edit'
    ])

    {{$skills->links()}}

@endsection,

