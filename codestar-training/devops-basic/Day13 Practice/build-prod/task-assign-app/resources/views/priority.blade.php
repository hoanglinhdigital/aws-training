@extends('layouts.create')

@section('form')
    <form action="#">
        <div class="form-group-row mb-3">
            @include('components.input-text', [
                'name' => 'priority_code', 
                'label' => 'Mã',
                'inputClass' => 'form-control d-inline w-75'
            ])
        </div>
        <div class="form-group-row mb-3">
            @include('components.input-text', [
                'name' => 'priority_name', 
                'label' => 'Tên',
                'inputClass' => 'form-control d-inline w-75'
            ])
        </div>
        <div class="form-group-row mb-5">
            @include('components.input-number', [
                'name' => 'priority', 
                'label' => 'Thứ tự ưu tiên'
            ])
        </div>

        @include('components.button-group', [
            'buttons' => [
                ['iconClass' => 'fas fa-save', 'value' => 'Lưu', 'action' => 'save'], 
                ['iconClass' => 'fas fa-trash', 'value' => 'Xóa', 'action' => 'delete'], 
            ] 
        ])
    </form>
@endsection

@section('table')
    @include('components.table', [
        'cols' => ['Mã', 'Tên'],
        'rows' => [
            ['ABC', 'CodeStar'],
            ['ABC', 'CodeStar'],
            ['ABC', 'CodeStar'],
            ['ABC', 'CodeStar'],
        ]
    ])
@endsection

