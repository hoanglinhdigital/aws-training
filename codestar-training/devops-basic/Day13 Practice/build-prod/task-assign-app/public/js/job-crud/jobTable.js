const handleRowsChange = () => {
    const tableIds = ['jobs-table', 'left-table', 'right-table'];
    
    tableIds.forEach(tableId => {
        $(`#${tableId} tbody tr.data-row`).each(function() {
            const id = $(this).attr('id');
            $(this).click(function() {
                handleRowClick(id);
            });
        });
    });

}


const handleRowClick = (id, readOnly=false) => {
    $('.alert').each(function () {
        $(this).alert('close');
    });

    $('#job_id').val(id).change();
    $('#history-workplan').show();
    initializeJobValues(id, readOnly);
    
    let url = $('#workplan').attr('href').split('/').slice(0, -1).join('/');
    $('#workplan').prop('href', `${url}/${id}`);

}

const addRowToTable = (tableId, idx,  data) => {
    let row = $('<tr/>', {
        'class': 'data-row',
    });    
    let content = $('<td/>', {id: idx}).append(data);
    row.append(content);

    $(`#${tableId} tbody`).append(row);
}

const resetTable = tableId => {
    $(`#${tableId} tbody tr`).remove();
}
