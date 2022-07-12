@extends('components.modal', [
    'title' => 'Kế hoạch thực hiện',
    'id' => 'workplan-modal'
])

@section('modal-body')
    <form action="{{ route('workplans.store') }}" method="POST">
        
        @csrf

        <input type="hidden" name="job_id" value="{{ $jobId ?? old('job_id') }}">
        <input type="hidden" name="type" value="{{ $type }}">

        <div class="form-group-row mb-3">
            @include('components.input-date', [
                'type' => 'date',
                'name' => 'from_date', 
                'label' => 'Từ ngày',
            ])

            @include('components.input-date', [
                'type' => 'date',
                'name' => 'to_date', 
                'label' => 'Đến ngày',
            ])
        </div>

        <div class="form-group-row mb-3">
            @include('components.input-date', [
                'type' => 'time',
                'name' => 'from_time', 
                'label' => 'Từ giờ',
            ])

            @include('components.input-date', [
                'type' => 'time',
                'name' => 'to_time', 
                'label' => 'Đến giờ',
            ])

        </div>

        <div class="form-group-row mb-3">
            @include('components.text-area', [
                'name' => 'content', 
                'label' => 'Nội dung'
            ])
        </div>

        @include('components.button-group', [
            'parentClass' => 'btn-group offset-5',
            'buttons' => [
                ['iconClass' => 'fas fa-save', 'value' => 'Lưu', 'action' => 'create'], 
                ['type' => 'button', 'iconClass' => 'fas fa-redo', 'value' => 'Tạo lại', 'action' => 'reset'], 
                
            ] 
        ])
        



    </form>

@endsection