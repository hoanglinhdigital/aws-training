@extends('components.dynamic-modal', [
    'title' => 'Thông tin nhân viên', 
    'id' => 'staff-info-modal'
])

@section('modal-body')
    <div class="container">

        <form action="{{ route('staff.update', ['id' => $staffId ?? '0']) }}" method="POST">
            @csrf
            
            
    
            <div class="form-group-row mb-3 offset-2">
                @include('components.input-text', [
                    'name' => 'name', 
                    'label' => 'Tên nhân viên',
                    'labelClass' => 'col-md-3 col-form-label p-0',
                    'inputClass' => 'form-control d-inline w-50'
                ])
    
    
            </div>
    
            <div class="form-group-row mb-3 offset-2">
                @include('components.input-text', [
                    'name' => 'dob', 
                    'label' => 'Ngày sinh',
                    'labelClass' => 'col-md-3 col-form-label p-0',
                    'inputClass' => 'form-control d-inline w-50'
                ])
    
    
            </div>

            <div class="form-group-row mb-3 offset-2">
                @include('components.input-text', [
                    'name' => 'phone', 
                    'label' => 'SĐT',
                    'labelClass' => 'col-md-3 col-form-label p-0',
                    'inputClass' => 'form-control d-inline w-50'
                ])
    
    
            </div>
    
            <div class="form-group-row mb-3 offset-2">
                @include('components.select', [
                    'name' => 'department_id', 
                    'label' => 'Phòng ban',
                    'options' => $departments,
                    'labelClass' => 'col-md-3 col-form-label p-0',
                    'selectClass' => 'custom-select w-50'
                ])
            </div>
    
            <div class="form-group-row mb-3 offset-2">
                @include('components.input-text', [
                    'name' => 'position', 
                    'label' => 'Vị trí',
                    'labelClass' => 'col-md-3 col-form-label p-0',
                    'inputClass' => 'form-control d-inline w-50'
                ])
            </div>
    
            <button type="submit" class="btn btn-primary offset-5">Cập nhật</button>
            
    
    
        </form>

    </div>
    

@endsection