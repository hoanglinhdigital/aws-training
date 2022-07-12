<div id="tree"></div>
<script src="{{ asset('js/common.js') }}"></script>
<script src="{{ asset('js/treeAPI.js') }}"></script>
<script src="{{ asset('js/assigneeCheckbox.js') }}"></script>

<script type="text/javascript">


    


    $(document).ready(function() {

        var jobAssigns = [];



        const tree = $('#tree').tree({
            uiLibrary: 'bootstrap4',
            dataSource: '/api/department',
            checkboxes: true,
            autoload: true,

        });

        const resetAssigneeTable = tableId => {
            $(`#${tableId} tbody tr`).remove();

            $('input[name="job_assigns[]"]').remove();

        }
        
        const renderAssigneeTable = (tableId) => {

            resetAssigneeTable(tableId);

            jobAssigns.forEach(jobAssign => {
                const assigneeInfo = jobAssign.data;
                addRowToAssigneeTable(tableId, assigneeInfo);
                addHiddenInput(assigneeInfo);
            });

        }

        const showWarningModal = (message) => {
            $('#warning-modal p').text(message);
            $('#warning-modal').modal('show');
        }


        tree.on('checkboxChange', function(e, $node, record, state) {

            if (record.id) {
               
           

                const assigneeName = record.text;
                const assigneeId = record.id;

                const processMethodId = $('#process_method').val();
                const processMethodName = $('#process_method option:selected').text();
                
                const duplicate = $(`#assignee-table tr[data-process-method-id="${processMethodId}"]#${assigneeId}`);

                if (state === 'checked') {

                    const assigneeInfo = {
                        assigneeId: assigneeId,
                        assigneeName: assigneeName,
                        processMethodId: processMethodId,
                        processMethodName: processMethodName,
                        sms: false,
                        directReport: false,
                        deadline: null

                    };
                    
                    jobAssigns.push({
                        id: $node.data('id'),
                        data: assigneeInfo
                    });

                    if (duplicate.length > 0) {
                       
                        tree.uncheck($node);   

                        showWarningModal('Đối tượng đã được chọn với hình thức xử lý này');



                    }
                    else if (processMethodName === 'chuyển tiếp') {

                        const forwardRows = $(`#assignee-table td.process_method:contains("${processMethodName}")`);

                        if (forwardRows.length > 0) {
                            tree.uncheck($node);   

                            showWarningModal('Chỉ được chuyển tiếp cho 1 đối tượng');
                        }
                        else {
                            renderAssigneeTable('assignee-table');
                        }

                    }
                    else if (processMethodName === 'chủ trì') {
                        const mainAssigneeRows = $(`#assignee-table td.process_method:contains("${processMethodName}")`);

                        if (mainAssigneeRows.length > 0) {
                            tree.uncheck($node);   

                            showWarningModal('Chỉ được bổ sung 1 đối tượng chủ trì');
                        }
                        else {
                            renderAssigneeTable('assignee-table');
                        }
                    }
                    else {
                        renderAssigneeTable('assignee-table');
                    }
                   

                    

                }
                else {


                    jobAssigns = jobAssigns.filter(jobAssign => jobAssign.id !== $node.data('id'));

                    renderAssigneeTable('assignee-table');

                }
                   
                



            }


        });

        const inputType = ['sms', 'direct_report', 'deadline'];

        inputType.forEach(type => {

            $('#assignee-table').on('change', `input.${type}`, function() {

                const parent = $(this).closest('tr');
                const assigneeId = parent.attr('id');
                const processMethodId = parent.data('processMethodId');

            

                const newValue = type === 'deadline' ? $(this).val() : this.checked;
                
                updateHiddenInput(`input[data-process-method-id="${processMethodId}"][id="${assigneeId}"]`, type, newValue);


            });
        });

        
        

    });
</script>
