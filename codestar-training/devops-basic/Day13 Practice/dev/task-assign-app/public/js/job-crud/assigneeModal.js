//MAIN FUNCTIONS

const toggleTickElement = (id, processMethod, assignee) => {
			
    let tickElement = $(`#assignee-list #${id} svg`);

    if (tickElement.css('display') === 'none') {
        

        if (processMethod === 'chu-tri') {
            $(`input[name="chu-tri[]"]`).remove();
            untickAssigneeTable();
        }
        const defaultDeadline = $('#deadline').val();

        let newInput = $('<input/>', {
            type: 'hidden', 
            name: `${processMethod}[]`,
            id: id,
            value: JSON.stringify({
                staff_id: id, 
                direct_report: false, 
                sms: false,
                deadline: defaultDeadline,
            })
        });

        $('#job-form').append(newInput);

        tickElement.show();

        addAssigneeToInput(processMethod, assignee);


        addRowToFullAssigneeTable('full-assignee-table', processMethod, id, assignee, false, false, defaultDeadline);
        
    }
    else {
        $(`input[name="${processMethod}[]"][id="${id}"]`).remove();
        tickElement.hide();

        removeAssigneeFromInput(processMethod, assignee);
        

        $(`#full-assignee-table tr[data-type="${processMethod}"]:contains("${id}")`).remove();

    }

    if (processMethod === 'chu-tri') {
        $('#assignee-modal').modal('hide');
    }
}



const search = (listId, assigneeId='', assigneeName='') => {

    const filteredRows = $(`#${listId} tbody tr`).filter(function() {
        if (assigneeId === '') 
            return  $(this).find('td.name').text().toLowerCase().includes(assigneeName.toLowerCase());
        
        if (assigneeName === '')
            return $(this).attr('id') === assigneeId;

        return $(this).attr('id') === assigneeId || $(this).find('td.name').text().toLowerCase().includes(assigneeName.toLowerCase());
    });

    filteredRows.each(function() {
        $(this).show();
    })

    $(`#${listId} tr`).not(filteredRows).hide();

}




const initializeAssigneeList = (assignees, readOnly) => {

    let mainAssigner = null;
    let colabAssigneeList = [];
    let supervisorAssigneeList = [];

    resetHiddenInputs();
    resetFullAssigneeTable('full-assignee-table');

    assignees.forEach(assignee => {
        
        const assigneeName = assignee.name;
        const processMethod = assignee.pivot.process_method;
        let mappedProcessMethod = null;

        switch (processMethod) {
            case "chủ trì":
                mappedProcessMethod = 'chu-tri';
                mainAssigner = assigneeName;
                break;
            
            case "phối hợp":
                mappedProcessMethod = 'phoi-hop';
                colabAssigneeList.push(assigneeName);
                break;

            case "nhận xét": 
                mappedProcessMethod = 'nhan-xet';
                supervisorAssigneeList.push(assigneeName);
                break;
        }



        let newInput = $('<input/>', {
            type: 'hidden', 
            name: `${mappedProcessMethod}[]`,
            id: assignee.id,
            value: JSON.stringify({
                staff_id: assignee.id, 
                direct_report: assignee.pivot.direct_report, 
                sms: assignee.pivot.sms,
                deadline: assignee.pivot.deadline
            })
        });

        $('#job-form').append(newInput);

        addRowToFullAssigneeTable('full-assignee-table', mappedProcessMethod, assignee.id, assigneeName, assignee.pivot.direct_report, assignee.pivot.sms, assignee.pivot.deadline, readOnly);

    });

    $('input#chu-tri-display').val(mainAssigner);

    $('input#phoi-hop-display').val(colabAssigneeList.join('; '));

    $('input#nhan-xet-display').val(supervisorAssigneeList.join('; '));


    
}


const initializeAssigneeDetailTable = (tableId, assigneeList) => {

    $(`#${tableId} tbody tr`).remove();

    assigneeList.forEach(item => {
        let row = $('<tr/>');
        Object.keys(item).forEach(field => {
            row.append($('<td/>').text(item[field]));
        })
        $(`#${tableId} tbody`).append(row);
    });


    
}




// HELPER FUNCTIONS

const untickAssigneeTable = () => {
    $('#assignee-list tr.data-row svg').hide();
}

const tickAssigneeTable = processMethod => {
    $(`input[name="${processMethod}[]"]`).each(function() {
        const value = $(this).attr('id');
        $(`#assignee-list #${value} svg`).show();
    });
}


const addAssigneeToInput = (processMethod, assignee) => {
    let assigneeList = [];
    switch(processMethod) {
                
        case 'chu-tri': 
            assigneeList = [assignee];							
            $('#chu-tri-display').val(assigneeList.join('; '));
            break;
        
        case 'phoi-hop': 
            assigneeList = $('#phoi-hop-display').val().split(';').filter(item => item !== '');	
            assigneeList.push(assignee);
            $('#phoi-hop-display').val(assigneeList.join('; '));
            break;
        
        case 'nhan-xet': 
            assigneeList = $('#nhan-xet-display').val().split(';').filter(item => item !== '');	
            assigneeList.push(assignee);
            $('#nhan-xet-display').val(assigneeList.join('; '));
            break;
    }
}


const addRowToFullAssigneeTable = (tableId,  processMethod, assigneeId, assigneeName, directReport=false, sms=false, deadline=null, readOnly=false) => {

    let row = $('<tr/>');


    switch (processMethod) {
        case 'chu-tri': 
            row.attr('data-type', 'chu-tri');
            row.append($('<td/>', {
                text: 'Chủ trì',
                class: 'process_method',
            }));
            break;

        case 'phoi-hop':
            row.attr('data-type', 'phoi-hop');
            row.append($('<td/>', {
                text: 'Phối hợp',
                class: 'process_method',
            }));
            break;

        case 'nhan-xet':
            row.attr('data-type', 'nhan-xet');
            row.append($('<td/>', {
                text: 'Theo dõi/Nhận xét',
                class: 'process_method',
            }));
            break;

    }

    row.append($('<td/>', {class: 'id'}).text(assigneeId));
    row.append($('<td/>', {class: 'name'}).text(assigneeName));
    row.append(
        $('<td/>').append($('<input/>', {
            type: 'checkbox',
            class: 'direct-report'
        })
        .prop('checked', directReport)
        .attr('disabled', readOnly)
    ));

    
    row.append(
        $('<td/>').append($('<input/>', {
            type: 'date',
            class: 'deadline',
            value: deadline,
        }).prop('readonly', readOnly))
    );
                    
    row.append(
        $('<td/>').append($('<input/>', {
            type: 'checkbox',
            class: 'sms'
        })
        .prop('checked', sms)
        .prop('disabled', readOnly)
    ));


    $(`#${tableId} tbody`).append(row);

}

const removeAssigneeFromInput = (processMethod, assignee) => {
    let assigneeList = [];

    switch(processMethod) {
        case 'chu-tri': 
            $('#chu-tri-display').val(null);
            break;
        
        case 'phoi-hop': 
            assigneeList = $('#phoi-hop-display').val().split(';').filter(item => item !== '').map(item => item.trim());	
            assigneeList = assigneeList.filter(item => item !== assignee);

            $('#phoi-hop-display').val(assigneeList.join('; '));
            break;
        
        case 'nhan-xet': 
            assigneeList = $('#nhan-xet-display').val().split(';').filter(item => item !== '').map(item => item.trim());	
            assigneeList = assigneeList.filter(item => item !== assignee);

            $('#nhan-xet-display').val(assigneeList.join('; '));
            break;
    }
}


const resetHiddenInputs = () => {
    $('input[name="chu-tri[]"]').remove();
    $('input[name="phoi-hop[]"]').remove();
    $('input[name="nhan-xet[]"]').remove();
}

const resetFullAssigneeTable = tableId => {
    $(`#${tableId} tbody tr`).remove();
}

const resetAssigneeDisplayValues = () => {
    
    $('input#chu-tri-display').val(null);

    $('input#phoi-hop-display').val(null);

    $('input#nhan-xet-display').val(null);
}

const displayAssigneeList = listId => {
    $(`#${listId} tbody tr`).show();
    $('#id').val(null);
    $('#assignee_name').val(null);
}



