@extends('layouts.job-crud')

@section('assign-button-group')
	@include('components.buttons', [
		'parentClass' => 'btn-group',
		'buttons' => [
			['iconClass' => 'fas fa-info-circle', 'value' => 'Xem chi tiết'], 
			['iconClass' => 'fas fa-plus', 'value' => 'Bổ sung'], 
		]
	])
@endsection

@section('button-group')

	@include('components.buttons', [
		'buttons' => [
			['iconClass' => 'fas fa-save', 'value' => 'Lưu'], 
			['iconClass' => 'fas fa-copy', 'value' => 'Lưu-sao'], 
			['iconClass' => 'fas fa-edit', 'value' => 'Sửa'], 
			['iconClass' => 'fas fa-trash', 'value' => 'Xóa'], 
			['iconClass' => 'fas fa-search', 'value' => 'Tìm kiếm'], 
		] 
	])
	
@endsection