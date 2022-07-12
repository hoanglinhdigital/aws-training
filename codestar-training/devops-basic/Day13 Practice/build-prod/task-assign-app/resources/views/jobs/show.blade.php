@extends('layouts.job-crud', [
	'routeName' => 'jobs.show',
	'method' => 'POST'
])

@section('assign-button-group')
	<a href="#" class="btn btn-link text-decoration-none">
		<i class="fas fa-info-circle"></i>
		<span>Xem chi tiết</span>
	</a>
@endsection

@section('button-group')

	@include('components.buttons', [
		'buttons' => [
			['iconClass' => 'fas fa-check', 'value' => 'Nhận việc', 'action' => 'accept'], 
			['iconClass' => 'fas fa-times', 'value' => 'Từ chối', 'action' => 'reject'], 
			['iconClass' => 'fas fa-comment', 'value' => 'Trao đổi', 'action' => 'exchange'], 
		] 
	])
	
@endsection