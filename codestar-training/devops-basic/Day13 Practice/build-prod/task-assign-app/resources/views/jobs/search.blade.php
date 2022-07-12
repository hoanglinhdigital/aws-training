@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4 ml-0">
            <form action="#" class="w-100">
                <div class="form-group-row mb-3">
                    @include('components.select', [
                        'name' => 'job_type',
						'label' => 'Loại công việc',
						'options' => ['Tất cả', 'Dự án', 'Training']
                    ])
                    @include('components.select', [
                        'name' => 'site.project.project',
						'label' => 'Dự án',
						'options' => ['Tất cả', 'CodeStar']
                    ])
                    
                </div>

                <div class="form-group-row mb-3">
                    @include('components.select', [
                        'name' => 'status',
                        'label'=> 'Trạng thái',
                        'options' => ['Tất cả', 'Chưa nhận', 'Đã giao', 'Chưa xử lý']
                    ])

                    @include('components.select', [
                        'name' => 'assessment',
                        'label'=> 'Đánh giá',
                        'options' => ['Tốt', 'Trung bình', 'Kém']
                    ])


                </div>

                <div class="form-group-row mb-3">
                    @include('components.select', [
                        'name' => 'assigner',
                        'label' => 'Người giao',
                        'options' => ['Tất cả', 'AVC', 'XYZ']
                    ])
                    @include('components.input-text', [
                        'name' => 'job_name',
                        'label' => 'Tên công việc'
                    ])

                   
                </div>
               
                <div class="form-group-row mb-3">
					@include('components.input-date', [
						'type' => 'date',
						'name' => 'from_date',
						'label' => 'Từ ngày'
					])
					@include('components.input-date', [
						'type' => 'date',
						'name' => 'to_date',
						'label' => 'Đến ngày'
					])

                </div>

				<div class="btn-group offset-5" role="group">
					<button type="submit" class="btn btn-light">
						<i class="fas fa-search"></i>
					</button>
					<button type="submit" class="btn btn-light">
						<i class="fas fa-redo"></i>
					</button>
				</div>

				<div class="row ml-0">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
						  <a class="nav-link active" id="direct-tab" data-toggle="tab" href="#direct" role="tab" aria-controls="direct" aria-selected="true">Công việc trực tiếp xử lý</a>
						</li>
						<li class="nav-item" role="presentation">
						  <a class="nav-link" id="related-tab" data-toggle="tab" href="#related" role="tab" aria-controls="related" aria-selected="false">Công việc liên quan</a>
						</li>
					</ul>
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="direct" role="tabpanel" aria-labelledby="direct-tab">
							{{-- TODO: Thêm bảng công việc xử lý trực tiếp --}}
						</div>
						<div class="tab-pane fade" id="related" role="tabpanel" aria-labelledby="related-tab">
							{{-- TODO: Thêm bảng công việc liên quan --}}

						</div>
					</div>
				</div>

				@include('components.button-group', [
					'parentClass' => 'btn-group offset-1',
					'buttons' => [
						['iconClass' => 'fas fa-info-circle', 'value' => 'Xem chi tiết', 'action' => 'detail'], 
						['iconClass' => 'fas fa-check', 'value' => 'Hoàn thành', 'action' => 'finish'], 
                        ['iconClass' => 'fas fa-search', 'value' => 'Tìm kiếm', 'action' => 'search'], 
						['iconClass' => 'fas fa-user-plus', 'value' => 'Giao xử lý', 'action' => 'assign'], 
						['iconClass' => 'fas fa-clipboard-list', 'value' => 'Timesheet', 'action' => 'timesheet'], 
                        ['iconClass' => 'fas fa-tasks', 'value' => 'Xác nhận SL', 'action' => 'amount_confirm'], 
						['iconClass' => 'fas fa-comments', 'value' => 'Trao đổi', 'action' => 'exchange'] 
					] 
				])

            </form>
        </div>

    </div>   
@endsection