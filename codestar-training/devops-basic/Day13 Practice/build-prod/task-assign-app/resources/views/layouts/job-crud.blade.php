@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-input.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dynamic-table.css') }}">


    @include('components.file-modal')

    @include('components.assignee-detail-modal')

    @yield('modal')


    <div class="container">

        @yield('message')


        <div class="row mb-5" id="created-date-wrapper">
            <div class="col offset-9">
                @include('components.input-text', [
                    'name' => 'created_date',
                    'labelClass' => 'form-label',
                    'label' => 'Ngày tạo',
                    'textClass' => 'd-inline ml-5',
                    'inputClass' => 'form-control d-inline w-50',
                    'readonly' => true
                ])
            </div>

            
        </div>

        <div class="row">

            
            <div class="col-md-9">

                @yield('form')

                <form id="job-form" action="{{route($routeName, $params ?? [])}}" method="{{$method}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="job_id" id="job_id" value="{{ $jobId ?? old('job_id') }}">
                    
                    <input type="hidden" name="editable" id="editable" value="{{ $editable ? 1 : 0 }}">

                    <input type="hidden" name= "process_method" id="process_method">

                    <input type="hidden" id="staff_id" value="{{ Auth::user()->staff_id }}">
                    
                    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
                        <legend class="w-auto">Thông tin nghiệp vụ</legend>
                        
                        @yield('job-info')
                        
                        
                    </fieldset>
					
                    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
                        <legend class="w-auto">Đối tượng xử lý</legend>


                        <div class="form-group-row mb-3 offset-10">
                            <button type="button" id="view-mode-btn" class="btn btn-info">Rút gọn</button>
                        </div>
                    
                        <div id="short-list">
                            <div class="form-group-row mb-3">
                                
                                @include('components.input-text', [
                                    'name' => 'chu-tri-display',
                                    'label' => 'Chủ trì', 
                                    'readonly' => true,
                                    'inputClass' => 'form-control d-inline w-75',
                                    'textClass' => 'col-sm-4 d-inline p-0'

                                ])

                                @if ($editable)
                                    <button type="button" id="chu-tri-btn" data-toggle="modal" data-target="#assignee-modal" class="btn btn-light">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @endif

                    
                            </div>
                            <div class="form-group-row mb-3">
                                @include('components.input-text', [
                                    'name' => 'phoi-hop-display',
                                    'label' => 'Phối hợp', 
                                    'readonly' => true,
                                    'inputClass' => 'form-control d-inline w-75',
                                    'textClass' => 'col-sm-4 d-inline p-0'


                                ])
                    
                                
                                @if ($editable)
                                    <button type="button" id="phoi-hop-btn" data-toggle="modal" data-target="#assignee-modal" class="btn btn-light">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @endif

                            </div>
                            <div class="form-group-row mb-3">
                                @include('components.input-text', [
                                    'name' => 'nhan-xet-display',
                                    'label' => 'Theo dõi/Nhận xét', 
                                    'readonly' => true,
                                    'inputClass' => 'form-control d-inline w-75',
                                    'textClass' => 'col-sm-4 d-inline p-0'

                                ])

                                
                                @if ($editable)
                                    <button type="button" id="nhan-xet-btn" data-toggle="modal" data-target="#assignee-modal" class="btn btn-light">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @endif

                            </div>
                        </div>
                    
                        <div id="full-list" style="display: none">
                            @include('components.dynamic-table', [
                                'cols' => [
                                    'Hình thức xử lý' => '',
                                    'Mã ĐT' => '',
                                    'Đối tượng xử lý' => '',
                                    'Báo cáo trực tiếp' => '',
                                    'Hạn xử lý' => '',
                                    'SMS' => '',
                                ],
                                'id' => 'full-assignee-table',
                                'rows' => [],
                            ])
                        </div>

                        @yield('assign-button-group')

                    </fieldset>

                    
					<div class="form-group-row mb-3 p-3">
						@include('components.input-text', [
							'name' => 'status', 
							'label' => 'Trạng thái',
                            'readonly' => true,
                            'value' => __('job.all_status.pending')
						])
					</div>

                    <div class="form-group-row mb-3 p-3" id="note-wrapper" style="display:  none">
                        @include('components.text-area', [
                            'name' => 'note',
                            'label' => 'Ghi chú sửa đổi',
                        ])
					</div>

                    @yield('deny-reason-modal')
                    
                    @yield('button-group')
                </form>
            </div>
            <div class="col-md-3">
                @yield('jobs-table')

                <div id="history-workplan" style="display: none">
                    <a href=""  class="btn btn-link p-0 mb-1 text-decoration-none" data-toggle="modal" data-target="#update-job-histories">Lịch sử công việc</a>
                    
                    <a href="{{ route('workplans.create', ['jobId' => $job_id ?? '0']) }}" id="workplan" class="btn btn-link p-0 text-decoration-none">Kế hoạch thực hiện</a>
                    

                    
                    <!-- Update job histories modal -->
                    <div class="modal fade" id="update-job-histories" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Lịch sử sửa đổi công việc</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @include('components.dynamic-table', [
                                    'id' => 'update-histories-table',
                                    'cols' => [
                                        'Ngày sửa đổi' => 'created_at',
                                        'Tên trường' => 'field', 
                                        'Giá trị cũ' => 'old_value', 
                                        'Giá trị thay đổi' => 'new_value',
                                        'Ghi chú sửa đổi' => 'note',
                                    ],
                                    'rows' => [],
                              
                                ])
                            </div>

                        </div>
                        </div>
                    </div>





                </div>
            </div>

        </div>
        
                
    </div>
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/job-crud/jobAPI.js') }}"></script>
    <script src="{{ asset('js/job-crud/jobFormInput.js') }}"></script>
    <script src="{{ asset('js/job-crud/jobTable.js') }}"></script>
    <script src="{{ asset('js/job-crud/updateJobHistories.js') }}"></script>
    <script src="{{ asset('js/job-crud/assigneeModal.js') }}"></script>

    <script type="text/javascript">
        
        $(document).ready(function () {

            initializeDefaultValues();
            handleSelectInputsChange();
            handleHistoryModalChange();            
            handleRowsChange();

            $('#view-mode-btn').click(function() {
				const text = $(this).html();
				if (text === 'Rút gọn') {
					
					$(this).html('Đầy đủ');
				
					$('#full-list').show();
					$('#short-list').hide();
				
				}
				else {
				
					$(this).html('Rút gọn');
				
					$('#short-list').show();
					$('#full-list').hide();
				}

			});

            $('button[value="assignee-detail"]').click(function() {

                const jobId = $('#job_id').val();

                getAssigneeList(jobId).then(assigneeList => {
                    initializeAssigneeDetailTable('assignee-detail-table', assigneeList);
                    $('#assignee-detail-modal').modal('show');
                });

            });
            
            setCloseTimeout("#successModal", 5000);
            setCloseTimeout("#errorModal", 5000);
        });

    </script>

    @yield('custom-script')

    
@endsection