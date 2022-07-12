@extends('layouts.app')

@section('content')
    <div class="container">

        <input type="hidden" name="job_id" id="job_id" value="{{ $jobId }}">

        <div class="row mb-4 ml-0">
            @include('components.select', [
                'selectClass' => 'custom-select w-75',
                'name' => 'assignee',
                'label' => 'Đối tượng xử lý',
                'options' => [],
            ])
            <label for="process_method" id="process_method">(Hình thức xử lý)</label>

        </div>
        <div class="row ml-0">
            @include('components.dynamic-table', [
                'id' => 'workplan-table',
                'cols' => [                              
                    'Từ ngày' => 'from_date',        
                    'Đến ngày' => 'to_date',               
                    'Từ giờ' => 'from_time',   
                    'Đến giờ' => 'to_time',
                    'Nội dung công việc' => 'content',
                ],
                'rows' => $workPlans ?? [],                       
                'min_row' => 4,                          
            ])
        </div>
    </div>

    <script src="{{ asset('js/job-crud/jobAPI.js') }}"></script>
    
    <script>

        const intializeOptionValues = (selectId, assigneeList) => {
            assigneeList.forEach(assignee => {
                
                let newOption = $('<option/>', {
                    value: assignee.id,
                    text: assignee.name
                });

                $(`#${selectId}`).append(newOption);
            });

        }

        const initializeWorkPlanTable = (tableId, workPlans) => {
            
            $(`#${tableId} tbody tr`).remove();


            workPlans.forEach(workPlan => {
                let newRow = $('<tr/>');
                
                Object.keys(workPlan).forEach(field => {
                    
                    if ($(`#${tableId} th[data-value="${field}"]`).length > 0) {    
                        const value = workPlan[field];
                        let newDataCol = $('<td/>', {text: value});
                        newRow.append(newDataCol);
                    }

                });

                $(`#${tableId} tbody`).append(newRow);
                
            });
        }

        const initializeOptionsAndTable = () => {

            const jobId = $('#job_id').val();

            getWorkPlans(jobId).then(jobAssigns => {


                let assigneeList = new Set();

                jobAssigns.forEach(jobAssign => {
                    assigneeList.add(jobAssign.assignee);
                });

                intializeOptionValues('assignee', assigneeList);

                $('#process_method').text(jobAssigns[0].process_method.name);

                const firstAssigneeWorkPlans = jobAssigns[0].work_plans;


                if (firstAssigneeWorkPlans.length > 0) {
                    initializeWorkPlanTable('workplan-table', firstAssigneeWorkPlans);
                }


            });
        }

        

        $(document).ready(function() {

            initializeOptionsAndTable();

            $('#assignee').change(function() {

                

                const assigneeId = $(this).find('option:selected').val();
                const jobId = $('#job_id').val();




                getWorkPlans(jobId, assigneeId).then(jobAssigns => {

                    $('#process_method').text(jobAssigns[0].process_method.name);

                    const workPlans = jobAssigns[0].work_plans;
                    initializeWorkPlanTable('workplan-table', workPlans);


                });

            });
        });
        
    </script>
@endsection