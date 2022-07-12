@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <form action="#">
                    <div class="form-group-row mb-3">
                        @include('components.input-text', [
                            'name' => 'job_name', 
                            'label' => 'Tên công việc'
                        ])
                    </div>
                    <div class="form-group-row mb-3">
                        @include('components.input-text', [
                            'name' => 'assignee', 
                            'label' => 'Đối tượng xử lý'
                        ])  
                        <label for="process_method">(Hình thức xử lý)</label>
                    </div>
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
                        @include('components.input-text', [
                            'name' => 'percentage_completed',
                            'label' => '% hoàn thành',
                        ])
                    </div>
                    <div class="form-group-row mb-3">
                        @include('components.text-area', [
                            'name' => 'content', 
                            'label' => 'Nội dung',
                        ])
                    </div>  
                    @include('components.button-group', [
                        'parentClass' => 'btn-group offset-3',
                        'buttons' => [
                            ['iconClass' => 'fas fa-save', 'value' => 'Ghi lại', 'action' => 'save'], 
                            ['iconClass' => 'fas fa-copy', 'value' => 'Ghi-sao', 'action' => 'save_copy'], 
                            ['iconClass' => 'fas fa-trash', 'value' => 'Xóa', 'action' => 'delete'], 
                            
                        ] 
                    ])
                </form>
            </div>
            <div class="col-md-4">
                {{-- TODO: Điền bảng ngày nhập --}}
            </div>
        </div>

    </div>
    
@endsection