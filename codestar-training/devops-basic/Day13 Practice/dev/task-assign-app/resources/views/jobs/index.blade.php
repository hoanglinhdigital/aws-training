@extends('layouts.app')

@section('content')
    <div class="container">

		@if(session('error'))
			@include('components.flash-message-modal', [
				'modalId' => 'errorModal',
				'alertClass' => 'alert alert-danger',
				'message' => session('error')
			])
		@endif
		


        <div class="row mb-4 ml-0">
            <form action="{{ route('jobs.search')}}" method="POST" class="w-100">
                @csrf

				<input type="hidden" name="type" value="{{ $type ?? 'all' }}">

				<div class="form-group-row mb-3">
                    @include('components.select', [
						'name' => 'job_type_id',
						'label' => 'Loại công việc',
						'options' => $jobTypes ?? [],
						'checked' => old('job_type_id')
					])
					@include('components.select', [
						'name' => 'project_id',
						'label' => 'Dự án',
						'options' => $projects ?? [],
						'checked' => old('project_id')
					])

                </div>

				@isset($all)
					<div class="form-group-row mb-3">
						@include('components.select', [
							'name' => 'status',
							'label' => 'Trạng thái',
							'options' => [],
							'checked' => old('status')
						])
						@include('components.select', [
							'name' => 'assessment',
							'label' => 'Đánh giá',
							'options' => [],
							'checked' => old('assessment')
						])

					</div>
				@endisset


                <div class="form-group-row mb-3">
                    @include('components.select', [
						'name' => 'assigner_id',
						'label' => 'Người giao',
						'options' => $assigners ?? [],
						'checked' => old('assigner_id')
					])

					@include('components.input-text', [
						'name' => 'name',
						'label' => 'Tên công việc'
					])
					
                    
                </div>
                <div class="form-group-row mb-3">
					@include('components.input-date', [
						'name' => 'from_date',
						'label' => 'Từ ngày',
						'type' => 'date',
					])
					@include('components.input-date', [
						'name' => 'to_date',
						'label' => 'Đến ngày',
						'type' => 'date',
					])
				</div>
				<div class="btn-group offset-5" role="group">
					<button type="submit" class="btn btn-light" name="action" value="search">
						<i class="fas fa-search"></i>
					</button>
					<button type="submit" id="reset-btn" class="btn btn-light" name="action" value="reset">
						<i class="fas fa-redo"></i>
					</button>

				</div>

			</form>

			<form action="{{ route('jobs.detailAction')}}" method="POST" class="w-100">
				@csrf

				<input type="hidden" name="type" value="{{ $type ?? 'all' }}">


				@isset($all)
			
					@yield('table')

				@else

					<div class="row ml-0 mb-5">
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
							<a class="nav-link active" id="left-tab" data-toggle="tab" href="#left" role="tab" aria-controls="left" aria-selected="true">{{ $left_title }}</a>
							</li>
							<li class="nav-item" role="presentation">
							<a class="nav-link" id="right-tab" data-toggle="tab" href="#right" role="tab" aria-controls="right" aria-selected="false">{{ $right_title }}</a>
							</li>
						</ul>
						<div class="tab-content w-100" id="myTabContent">
							<div class="tab-pane fade show active w-100" id="left" role="tabpanel" aria-labelledby="left-tab">

								@yield('left-tab-table')
								
							</div>
							<div class="tab-pane" id="right" role="tabpanel" aria-labelledby="right-tab">

								@yield('right-tab-table')
								
							</div>
						</div>
					</div>

				@endisset
				

				@yield('button-group')
				
				<div id="detail-button-group">
					@include('components.button-group', [
						'parentClass' => 'btn-group offset-1',
						'buttons' => [
							['iconClass' => 'fas fa-info-circle', 'value' => 'Xem chi tiết', 'action' => 'detail'], 
							['iconClass' => 'fas fa-check', 'value' => 'Hoàn thành', 'action' => 'finish'], 
							['iconClass' => 'fas fa-search', 'value' => 'Tìm kiếm', 'action' => 'search'], 
							['iconClass' => 'fas fa-user-plus', 'value' => 'Giao xử lý', 'action' => 'assign'], 
							['iconClass' => 'fas fa-clipboard-list', 'value' => 'Timesheet', 'action' => 'timesheet'],
							['iconClass' => 'fas fa-plus', 'value' => 'Tạo việc', 'action' => 'job_create'], 
							['iconClass' => 'fas fa-tasks', 'value' => 'Xác nhận SL', 'action' => 'amount_confirm'], 
							['iconClass' => 'fas fa-comments', 'value' => 'Trao đổi', 'action' => 'exchange'] 
						] 
					])
				</div>



            </form>
        </div>
    </div>   

	<script>
		const disableButtonGroup = () => {
			$('#detail-button-group button').prop('disabled', true);
		}

		const enableButtonGroup = () => {
			$('#detail-button-group button').prop('disabled', false);
		}

		$(document).ready(function () {

			disableButtonGroup();
			
			$('th input:checkbox').prop('disabled', true);
			
			$('input:checkbox').each(function() {
				$(this).prop('checked', false);
			});




			$('#left-table thead th input:checkbox').change(function() {
				$('#left-table input:checkbox').not(this).prop('checked', this.checked).change();
			});

			$('#right-table thead th input:checkbox').change(function() {
				$('#right-table input:checkbox').not(this).prop('checked', this.checked).change();
			});

			$('#left tbody input:checkbox').change(function() {
				if (this.checked) {
					const jobId = $(this).closest('tr').attr('id');
					$(this).val(jobId);

					const processMethod = $(this).closest('tr').find('td.process_method').text();

					enableButtonGroup();
					
					$('tr.data-row').each(function() {
						if ($(this).find('td.process_method').text() !== processMethod) {
							$(this).find('input:checkbox').prop('disabled', true);
						}
					});

				}
				else {
					$('#left thead input:checkbox').prop('checked', false);
					$(this).removeAttr('value');

					if ($('input:checkbox:checked').length === 0) {
						$('tr.data-row input:checkbox').prop('disabled', false);
					}

					if ($('#left tbody input:checkbox:checked').length === 0) {
						disableButtonGroup();
					}
				}
			});

			$('#right tbody input:checkbox').change(function() {
				if (this.checked) {
					const jobId = $(this).closest('tr').attr('id');
					$(this).val(jobId);

					const processMethod = $(this).closest('tr').find('td.process_method').text();


					$('tr.data-row').each(function() {
						if ($(this).find('td.process_method').text() !== processMethod) {
							$(this).find('input:checkbox').prop('disabled', true);
						}
					});

					enableButtonGroup();						
				}
				else {
					$('#right thead input:checkbox').prop('checked', false);
					$(this).removeAttr('value');

					if ($('input:checkbox:checked').length === 0) {
						$('tr.data-row input:checkbox').prop('disabled', false);
					}

					if ($('#right tbody input:checkbox:checked').length === 0) {
						disableButtonGroup();
					}
				}
			});


			const setCloseTimeout = (modalSelector, timeout) => {
				$(modalSelector).modal("show").on("shown.bs.modal", function () {
					window.setTimeout(function () {
						$(modalSelector).modal("hide");
					}, timeout);
				});
 		    }

        	setCloseTimeout("#errorModal", 5000);
		})
	</script>

	@yield('custom-script')

		
    </div>   



@endsection