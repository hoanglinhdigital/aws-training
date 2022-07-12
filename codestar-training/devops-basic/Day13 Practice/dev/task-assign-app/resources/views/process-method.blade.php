
@extends('layouts.create')

@section('form')
    <form action="#">
        <div class="form-group-row mb-3">
            @include('components.input-text', [
                'name' => 'process_method_code', 
                'label' => 'Mã',
                'inputClass' => 'form-control d-inline w-75'
            ])
        </div>
        <div class="form-group-row mb-3">
            @include('components.input-text', [
                'name' => 'process_method_name', 
                'label' => 'Tên',
                'inputClass' => 'form-control d-inline w-75'
            ])
        </div>
        <div class="form-group-row mb-5">
            @include('components.select', [
                'name' => 'assigner', 
                'label' => 'Chủ trì',
                'options' => ['Không', 'Có'],
            ])
        </div>

        @include('components.button-group', ['buttons' => ['Lưu', 'Xóa']])
    </form>
@endsection

@section('table')
    @include('components.table', [
        'cols' => ['Mã', 'Hình thức xử lý'],
        'rows' => [
            ['ABC', 'Chủ trì'],
            ['ABC', 'Phối hợp'],
            ['ABC', 'Đánh giá'],
        ]
    ])
    
@endsection