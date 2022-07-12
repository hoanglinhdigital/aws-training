@extends('layouts.create')

@section('form')
    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
        <legend class="w-auto">Độ ưu tiên</legend>
        @include('components.flash-message')
        <form action="{{route('priority.update',['id'=>$priority->id])}}" method="POST">
            @csrf
            <div class="form-group-row mb-3">
                @include('components.input-text', [
                    'name' => 'priority_code',
                    'label' => 'Mã',
                    'value' => $priority->code,
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            <div class="form-group-row mb-3">
                @include('components.input-text', [
                    'name' => 'priority_name',
                    'label' => 'Tên',
                    'value' => $priority->name,
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            <div class="form-group-row mb-5">
                @include('components.input-number', [
                    'name' => 'priority_number',
                    'label' => 'Thứ tự ưu tiên',
                    'value' => $priority->priority,
                    'inputClass' => 'form-control d-inline w-75'
                ])
            </div>
            @include('components.button-group', [
                'buttons' => [
                    ['iconClass' => 'fas fa-save', 'value' => 'Lưu' ],
                ]
            ])
            @include('components.span-modal', [
                   'value' => 'Xóa'
               ])
        </form>
        @include('components.warning-modal', [
            'href' => route('priority.destroy',['id'=>$priority->id])
        ])
        @include('components.span-modal', [
             'value' => 'Xóa'
         ])
    </form>

    @include('components.modal', [
        'href' => route('priority.destroy',['id'=>$priority->id]),
        'messages' => 'Bạn có chắc chắn xóa độ ưu tiên này không?'
    ])
@endsection

@section('table')
    @include('components.table', [
        'fields' => [
            'code' => 'code',
            'name_priority' => 'name',
            'edit' => 'pattern.modified',
           ],
        'items' => $priorities,
        'edit_route' => 'priority.edit'
    ])
    {{$priorities->links()}}
@endsection

