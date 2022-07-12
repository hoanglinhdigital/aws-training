@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="#" class="offset-4">
            <div class="form-group-row mb-3">
                @include('components.select', [
                    'labelClass' => 'col-sm-3 col-form-label p-0',
                    'name' => 'period', 
                    'label' => 'Kỳ',
                    'options' => ['Không', 'Bắt buộc'],
                ])
            </div>
            <div class="form-group-row mb-3">
                @include('components.select', [
                    'labelClass' => 'col-sm-3 col-form-label p-0',
                    'name' => 'project_code', 
                    'label' => 'Mã công việc',
                    'options' => ['Không', 'Bắt buộc'],
                ])
            </div>
            <div class="form-group-row mb-3">
                @include('components.select', [
                    'labelClass' => 'col-sm-3 col-form-label p-0',
                    'name' => 'lsx_amount', 
                    'label' => 'Khối lượng LSX',
                    'options' => ['Không', 'Bắt buộc'],
                ])
            </div>

            <div class="form-group-row mb-3">
                @include('components.select', [
                    'labelClass' => 'col-sm-3 col-form-label p-0',
                    'name' => 'assign_amount', 
                    'label' => 'Khối lượng giao',
                    'options' => ['Không', 'Bắt buộc'],
                ])
            </div>

            <div class="form-group-row mb-3">
                @include('components.select', [
                    'labelClass' => 'col-sm-3 col-form-label p-0',
                    'name' => 'job_accept', 
                    'label' => 'Nhận việc',
                    'options' => ['Không', 'Bắt buộc'],
                ])
            </div>

            <div class="form-group-row mb-5">
                @include('components.select', [
                    'labelClass' => 'col-sm-3 col-form-label p-0',
                    'name' => 'work_plan', 
                    'label' => 'Kế hoạch thực hiện',
                    'options' => ['Không', 'Bắt buộc'],
                ])
            </div>
            <div class="row offset-3">
                <button type="submit" class="btn btn-info">
                    <i class="fas fa-save"></i>
                    <span>Lưu</span> 
                </button>
            </div>
            
        </form>
            
    </div>
@endsection