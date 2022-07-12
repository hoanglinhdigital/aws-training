@extends('layouts.app')

@section('content')


    <link rel="stylesheet" href="{{ asset('css/assign-list.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dynamic-table.css') }}">

    @include('components.flash-message-modal', [
        'modalId' => 'warning-modal',
        'alertClass' => 'alert alert-warning',
        'message' => '',
    ])

    @if ($errors->any())
        @include('components.flash-message-modal', [
            'modalId' => 'error-modal',
            'alertClass' => 'alert alert-danger',
            'message' => $errors->all()[0],
        ])
        
    @endif

    @if (session('success'))
        @include('components.flash-message-modal', [
            'modalId' => 'success-modal',
            'alertClass' => 'alert alert-success',
            'message' => session('success')
        ])
    @endif

    @if (session('error'))
        @include('components.flash-message-modal', [
            'modalId' => 'error-modal',
            'alertClass' => 'alert alert-danger',
            'message' => session('error')
        ])
    @endif

    
    <div class="container">
        <div class="row-mb-3 ml-0">
            <div class="job-list">
                @include('components.dynamic-table', [
                    'cols' => [
                        'Tên công việc' => 'name',
                        'Người giao việc' => 'assigner_name',
                        'Khối lượng công việc' => 'assign_amount',
                        'Hạn xử lý' => 'deadline',
                    ],
                    'rows' => $jobs ?? [],
                ])
            </div>
        </div>
        <div class="col-md-12">
            @include('components.icon-button-group', [
                'buttonList' => [ 'cut' , 'list-ul' ]
            ])
        </div>
        <div class="assignee-list">
            <div class="row ml-0">




                <div class="col-md-4 p-0">
                    @include('components.checkboxes')
                </div>
                <div class="col-md-8">
                    <form action="{{ route('assignee-list.action') }}" method="POST" class="w-100">
                        @csrf
                        
                        <input type="hidden" name="staff_id" id="staff_id" value="{{ Auth::user()->staff_id }}">

                        <div class="form-group-row">



                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="left-tab" data-toggle="tab" href="#left" role="tab" aria-controls="left" aria-selected="true">Bổ sung/Chuyển tiếp</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="right-tab" data-toggle="tab" href="#right" role="tab" aria-controls="right" aria-selected="false">Đối tượng đã chọn xử lý</a>
                                </li>
                            </ul>
                            <div class="tab-content w-100 mt-3" id="myTabContent">
                                <div class="tab-pane fade show active w-100" id="left" role="tabpanel" aria-labelledby="left-tab">
                                    
                                    <div class="form-group-row mb-3">
                                        @include('components.select', [
                                            'name' => 'process_method',
                                            'label' => 'Hình thức xử lý',
                                            'options' => $processMethods
                                        ])
                                    </div>

                                    @include('components.dynamic-table', [
                                        'id' => 'assignee-table',
                                        'cols' => [
                                            'Đối tượng xử lý' => 'name',
                                            'Hình thức xử lý' => 'processMethod',
                                            'Báo cáo trực tiếp' => '',
                                            'Hạn xử lý' => 'deadline',
                                            'SMS' => 'sms'
                                        ],
                                        'rows' => $assignees ?? [],
                                        
                                    ])          
                                    
                                </div>
                                <div class="tab-pane" id="right" role="tabpanel" aria-labelledby="right-tab">
            
                                    <div class="form-group-row mb-3">
                                        @include('components.searchable-input-text', [
                                            'name' => 'jobs',
                                            'label' => 'Công việc',
                                            'options' =>  $jobs ?? []
                                        ])
                                    </div>

                                    @include('components.dynamic-table', [
                                        'id' => 'added-assignee-table',
                                        'cols' => [
                                            'Đối tượng xử lý' => 'name',
                                            'Hình thức xử lý' => 'processMethod',
                                            'Báo cáo trực tiếp' => '',
                                            'Hạn xử lý' => 'deadline',
                                            'SMS' => 'sms',
                                            'checkbox' => 'old_job_assigns'
                                        ],
                                        'rows' =>  [],
                                        
                                    ])

                                    @include('components.button-group', [
                                        'parentClass' => 'btn-group float-right',
                                        'buttons' => [
                                            ['iconClass' => 'fas fa-cut', 'value' => '', 'action' => 'delete'], 
                                            
                                        ] 
                                    ])

                                    
                                </div>
                            </div>



                        </div>

                        <div class="button-group text-center" id="btn-list">
                            <button type="button" class="btn btn-light">
                                <i class="fas fa-check"></i>
                                <span>Chọn</span>
                            </button>
                            <button type="submit" name="action" value="save" class="btn btn-light">
                                <i class="fas fa-forward"></i>
                                <span>Chuyển</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/job-crud/jobAPI.js') }}"></script>

    <script>

        const initializeJobHiddenInputs = () => {
            $('.job-list tr.data-row').each(function() {
                let newInput = $('<input/>', {
                    type: 'hidden',
                    name: 'job_ids[]',
                    value: $(this).attr('id')
                });

                $('form').append(newInput);
            });

            
        }

        

        const initializeAssigneeTable = () => {

            
            const assigneeId = $('#staff_id').val();

            let jobIds = [];

            $('input[name="job_ids[]"]').each(function() {
                jobIds.push($(this).val());
            });





            if (jobIds.length > 0) {
                getJobAssigns(assigneeId, jobIds).then(jobAssigns => {
                    jobAssigns.forEach(jobAssign => {

                        const data = {
                            id: jobAssign.id,
                            assigneeId: jobAssign.staff_id,
                            assigneeName: jobAssign.assignee.name,
                            jobId: jobAssign.job_id,
                            processMethodId: jobAssign.process_method_id,
                            processMethodName: jobAssign.process_method.name,
                            directReport: jobAssign.direct_report,
                            sms: jobAssign.sms,
                            deadline: jobAssign.deadline,
                            readonly: !jobAssign.self_assigned
                        };

                        addRowToAssigneeTable('added-assignee-table', data, true);
                    });
                });
            }
        }

        const displayJobAssignsByJobId = (tableId, jobId) => {
            if (!jobId) {
                $(`#${tableId} tbody tr`).show();
            }
            else {
                $(`#${tableId} tbody tr`).hide();

                $(`#${tableId} tbody tr[data-job-id="${jobId}"]`).show();
            }

        }


        $(document).ready(function() {


            initializeJobHiddenInputs();

            initializeAssigneeTable();

            $('#jobs').change(function(e) {
                const jobId = e.target.value;

                displayJobAssignsByJobId('added-assignee-table', jobId);
            });





            $('#added-assignee-table tbody').change(function(e) {
                
                const target = $(e.target);


                if (target.attr('name') !== 'job_assign_ids[]') {
                    let newVal = null;
                    
                    if (target.attr('type') === 'checkbox') {
                        newVal = target.prop('checked');
                    }
                    else {
                        newVal = target.val();
                    }

                    
                    const jobAssignId = target.closest('tr').attr('id');
                    const processMethodId = target.closest('tr').find('select').val();
                    const sms = target.closest('tr').find('.sms').prop('checked');
                    const directReport = target.closest('tr').find('.direct_report').prop('checked');
                    const deadline = target.closest('tr').find('.deadline').val();


                    let input = $(`input[name="old_job_assigns[]"][id="${jobAssignId}"]`);

                    if (input.length === 0) {
                        addOldJobAssignInput({
                            id: jobAssignId,
                            processMethodId: processMethodId,
                            sms: sms,
                            directReport: directReport,
                            deadline: deadline ? deadline : null,
                        })
                    }
                    else {
                        const jobAssign = JSON.parse(input.val());

                        input.val(JSON.stringify({
                            ...jobAssign,
                            [target.attr('class')]: newVal
                        }));
                    }

                }

                else {
                    const checked = target.prop('checked');
                 
                    const jobAssignId = target.closest('tr').attr('id');

                    if (checked) {
                        $('form').append($('<input/>', {
                            type: 'hidden',
                            name: 'delete_ids[]',
                            value: jobAssignId
                        }));
                    }
                    else {
                        $(`input[name="delete_ids[]"][value="${jobAssignId}"]`).remove();
                    }
                }

                




            });


            $('#warning-modal').on("shown.bs.modal", function () {
                window.setTimeout(function () {
                    $('#warning-modal').modal("hide");
                }, 3000);
            });

            $('#error-modal').modal("show").on("shown.bs.modal", function () {
                window.setTimeout(function () {
                    $('#error-modal').modal("hide");
                }, 5000);
            });

            $('#success-modal').modal("show").on("shown.bs.modal", function () {
                window.setTimeout(function () {
                    $('#success-modal').modal("hide");
                }, 5000);
            });
            
            

        });


        




    </script>


@endsection
