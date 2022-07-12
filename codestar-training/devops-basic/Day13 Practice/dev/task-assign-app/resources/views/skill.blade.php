@extends('layouts.create')

@section('form')
    <form action="#">
        <div class="form-group-row mb-3">
            @include('components.input-text', [
                'name' => 'skill_code', 
                'label' => 'Mã',
                'inputClass' => 'form-control d-inline w-75'
            ])
        </div>
        <div class="form-group-row mb-5">
            @include('components.input-text', [
                'name' => 'skill_name', 
                'label' => 'Tên',
                'inputClass' => 'form-control d-inline w-75'
            ])
        </div>
        @include('components.button-group', ['buttons' => ['Lưu', 'Xóa']])
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