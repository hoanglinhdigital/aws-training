@extends('layouts.app')

@section('content')

    @if (session('success'))
        @include('components.flash-message-modal', [
            'modalId' => 'success-modal',
            'alertClass' => 'alert alert-sucess',
            'message' => session('success')
        ])

        {{ session()->put('success', null) }}

    @endif

    @if (session('error')) 
    
        @include('components.flash-message-modal', [
            'modalId' => 'error-modal',
            'alertClass' => 'alert alert-danger',
            'message' => session('error')
        ])

        {{ session()->put('error', null) }}

    @endif

    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <form action="{{ route('amount-confirms.action') }}" method="POST" class="w-100">
                    @csrf

                    <input type="hidden" name="amount_confirm_id" id="amount_confirm_id">
                    <input type="hidden" name="job_id" id="job_id" value="{{ $job->id }}">

                    <div class="form-group-row mb-3">
                        @include('components.input-date', [
                            'type' => 'month',
                            'name' => 'month', 
                            'label' => 'Tháng',
                        ])
                    </div>
    
                    <div class="form-group-row mb-3">
                        @include('components.select', [
                            'name' => 'assignee',
                            'label' => 'Người xử lý',
                            'options' => $job->assignees ?? []
                        ])
                    </div>
    
                    <div class="form-group-row mb-3">
                        @include('components.input-text', [
                            'name' => 'assign_amount', 
                            'label' => 'KL giao',
                            'value' => $job->assign_amount,
                            'readonly' => true
                        ])
                    </div>
    
                    <div class="form-group-row mb-3">
                        @include('components.input-text', [
                            'name' => 'old_confirm_amount', 
                            'label' => 'KL cũ đã xác nhận',
                            'value' => $job->old_confirm_amount ?? null,
                            'readonly' => true
                        ])
                        @include('components.input-text', [
                            'name' => 'old_confirm_percentage', 
                            'label' => '% đã xác nhận',
                            'value' => $job->old_confirm_percentage ?? null,
                            'readonly' => true

                        ])

                    </div>
                    <div class="form-group-row mb-3">
                        @include('components.input-text', [
                            'name' => 'request_amount', 
                            'label' => 'KL đề nghị',
                            'value' => $job->request_amount ?? null,
                            'readonly' => true
                        ])
                        @include('components.input-text', [
                            'name' => 'request_percentage', 
                            'label' => '% đề nghị',
                            'value' => $job->request_percentage ?? null,
                            'readonly' => true
                        ])

                    </div>
                    <div class="form-group-row mb-3">
                        @include('components.input-number', [
                            'name' => 'confirm_amount', 
                            'label' => 'KL CV',
                        ])

                        @include('components.input-text', [
                            'name' => 'confirm_percentage', 
                            'label' => '% hoàn thành',
                            'value' => 0,
                            'readonly' => true
                        ])

                    </div>
                    
                    @error('confirm_amount')
                        <div class="form-group-row my-5">
                            <span class="alert alert-danger w-100">{{ $errors->first('confirm_amount') }}</span>
                        </div>
                    @enderror

                    <div class="form-group-row mb-3">
                        @include('components.select', [
                            'name' => 'quality',
                            'label' => 'Chất lượng',
                            'options' => ['Tốt', 'Khá', 'Trung bình', 'Kém'],
                        ])
  
                    </div>
  
                    <div class="form-group-row mb-5">
                        @include('components.text-area', [
                            'name' => 'note',
                            'label' => 'Nhận xét',
                        ])

                    </div>

                    @include('components.button-group', [
                        'buttons' => [
                            ['iconClass' => 'fas fa-save', 'value' => 'Lưu', 'action' => 'save'], 
                            ['iconClass' => 'fas fa-trash', 'value' => 'Xóa', 'action' => 'delete'], 
                            ['iconClass' => 'fas fa-clipboard-list', 'value' => 'Timesheet', 'action' => 'timesheet'], 
                            ['iconClass' => 'fas fa-redo', 'value' => 'Tạo mới', 'action' => 'reset'], 
                        ] 
                    ])
                </form>
                
            </div>

            <div class="col-md-3">
                @include('components.dynamic-table', [
                    'id' => 'amount-confirms-table',
                    'cols' => [
                        'Tháng' => 'month',
                        'Đối tượng xử lý' => 'assignee_name'
                    ],
                    'rows' => $amountConfirms ?? []
                ])
            </div>
        </div>
    </div>

    <script src="{{ asset('js/amountConfirmApi.js') }}"></script>
    <script src="{{ asset('js/amountConfirm.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>

    <script>

        $(document).ready(function() {

            initializeMonthValue();

            const month = $('#month').val();
            const staffId = $('#assignee').val();
            const jobId = $('#job_id').val();

            const params = JSON.stringify({month, staffId, jobId});

            queryAmountConfirm(params).then(amountConfirm => {
                if (amountConfirm) {
                    initializeInputValues(amountConfirm);
                }
            });


            $('#month, #assignee').change(function(e) {
                const month = $('#month').val();
                const staffId = $('#assignee').val();
                const jobId = $('#job_id').val();

                const params = JSON.stringify({month, staffId, jobId});

                queryAmountConfirm(params).then(amountConfirm => {
                    if (amountConfirm) {
                        initializeInputValues(amountConfirm);
                    }
                    else {
                        resetInputValues();
                    }
                });
            });

            $('#amount-confirms-table tbody tr.data-row').click(function() {
                
                const id = $(this).attr('id');

                queryAmountConfirm(JSON.stringify({id})).then(amountConfirm => {
                    if (amountConfirm) {
                        initializeInputValues(amountConfirm);
                    }
                    else {
                        resetInputValues();
                    }
                });
            });

            $('#confirm_amount').on('change keyup', function() {

                const confirmAmount = parseInt($(this).val());
                const oldConfirmAmount = parseInt($('#old_confirm_amount').val());
                const assignAmount = parseInt($('#assign_amount').val());
                
                
                if (confirmAmount) {
                    $('#confirm_percentage').val((oldConfirmAmount + confirmAmount) * 100 / assignAmount);
                }
                else {
                    $('#confirm_percentage').val(0);

                }

            });

            setCloseTimeout("#success-modal", 5000);
            setCloseTimeout("#error-modal", 5000);

        });



    </script>
    
@endsection