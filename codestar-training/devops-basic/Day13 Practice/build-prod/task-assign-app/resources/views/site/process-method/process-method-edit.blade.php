@extends('layouts.create')

@section('form')
    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
        <legend class="w-auto">Hình thức xử lý</legend>
        @include('components.flash-message')
        <form action="{{route('process-method.update',['id'=>$process_method->id])}}" method="POST">
            @csrf
            <div class="form-group-row mb-3">
                @include('components.input-text', [
                    'name' => 'process_code',
                    'label' => 'Mã code',
                    'value' => $process_method->code,
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            <div class="form-group-row mb-5">
                @include('components.input-text', [
                    'name' => 'process_name',
                    'label' => 'Hình thức xử lý',
                    'value' => $process_method->name,
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            <div class="form-group-row mb-5">
                @include('components.select', [
                    'name' => 'process_assigner',
                    'label' => 'Chủ trì',
                    'options' => [
                        ['value' => 0, 'display' => 'Không' ],
                        ['value' => 1, 'display' => 'Có' ],
                    ],
                    'checked' => $process_method->assigner,
                ])
            </div>
            @include('components.buttons', [
                'buttons' => [
                    ['iconClass' => 'fas fa-save', 'value' => 'Lưu' ],
                ]
            ])
            @include('components.span-modal', [
               'value' => 'Xóa'
           ])
            </form>
        @include('components.warning-modal', [
                'href' => route('process-method.destroy',['id'=>$process_method->id]),
                'messages' => 'Bạn có chắc chắn xóa hình thức xử lý này không?'
            ])
    </fieldset>
@endsection

@section('table')
    @include('components.table', [
        'fields' => [
            'code' => 'code',
            'name_process' => 'name',
            'edit' => 'pattern.modified'
           ],
        'items' => $process_methods,
        'edit_route' => 'process-method.edit'
    ])

    {{$process_methods->links()}}

@endsection,

