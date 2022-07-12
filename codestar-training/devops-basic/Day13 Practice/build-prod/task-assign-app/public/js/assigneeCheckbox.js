const addRowToAssigneeTable = (tableId, data, addCheckboxCol=false) => {


    let row = $('<tr/>', {
        id: data.id ? data.id : data.assigneeId,
        'data-process-method-id': data.processMethodId
    });
    if (data.jobId) {
        row.attr('data-job-id', data.jobId);
    }

    row.append($('<td/>', {class: 'name'}).text(data.assigneeName));


    if (!data.readonly) {
        let processMethodOptions = $('<select/>', {
            name: 'process_method_id',
            class: 'process_method_id'
        });
        
        $('#process_method option').each(function() {
            processMethodOptions.append($('<option/>', {
                value: $(this).val(),
                text: $(this).text()
            })
            .prop('selected', data.processMethodId === $(this).val())
            );
        });

        row.append($('<td/>', {class: 'process_method'}).append(processMethodOptions));

    }
    else {
        row.append($('<td/>', {class: 'process_method'}).text(data.processMethodName));
    }

    row.append(
        $('<td/>').append($('<input/>', {
            type: 'checkbox',
            class: 'direct_report'
        })
        .prop('checked', data.directReport)
        .prop('disabled', data.readonly)
    ));

    row.append(
        $('<td/>').append($('<input/>', {
            type: data.readonly ? 'text' : 'date',
            class: 'deadline',
            value: data.deadline,
            readonly: data.readonly
        }))
    );
                    
    row.append(
        $('<td/>').append($('<input/>', {
            type: 'checkbox',
            class: 'sms'
        })
        .prop('checked', data.sms)
        .prop('disabled', data.readonly)

    ));      

    if (addCheckboxCol) {
        row.append(
            $('<td/>').append($('<input/>', {
                name: 'job_assign_ids[]',
                type: 'checkbox',
            })
            .prop('disabled', data.readonly)
    
        ));     
    }

    $(`#${tableId} tbody`).append(row);


}

const removeFromAssigneeTable = (tableId, rowId, processMethodId) => {

    $(`#${tableId} tbody tr[data-process-method-id="${processMethodId}"]#${rowId}`).remove();
}


const addHiddenInput = data => {
    let newInput = $('<input/>', {
        type: 'hidden',
        'data-process-method-id': data.processMethodId, 
        name: 'job_assigns[]',
        id: data.assigneeId,
        value: JSON.stringify({
            staff_id: data.assigneeId, 
            process_method_id: data.processMethodId,
            sms: data.sms,
            deadline: null,
            direct_report: data.directReport
        })
    });


    $('form').append(newInput);
}

const addOldJobAssignInput = data => {
    let newInput = $('<input/>', {
        type: 'hidden',
        name: 'old_job_assigns[]',
        id: data.id,
        value: JSON.stringify({
            id: data.id,
            staff_id: data.assigneeId, 
            process_method_id: data.processMethodId,
            sms: data.sms,
            deadline: data.deadline,
            direct_report: data.directReport
        })
    });


    $('form').append(newInput);
}

const removeHiddenInput = (inputSelector) => {

    $(inputSelector).remove();
}

const updateHiddenInput = (inputSelector, field, value) => {

    const oldValue = JSON.parse($(inputSelector).val());
    
    $(inputSelector).val(JSON.stringify({
        ...oldValue,
        [field]: value
    }));
}
