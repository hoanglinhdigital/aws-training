const handleHistoryModalChange = () => {
    $('#update-job-histories').on('show.bs.modal', generateUpdateHistoriesTable);
    
    $('#update-job-histories').on('hidden.bs.modal', resetUpdateHistoriesTable);
}

const generateUpdateHistoriesTable = () => {
    const jobId = $('#job_id').val();

    getUpdateHistories(jobId).then(histories => {
        histories.forEach(his => {
            console.log(his.created_at);
            let row = $('<tr/>', {
                id: his.id,
                'class': 'data-row',
            });    
        
            addDataToRow(row, his);

            $('#update-job-histories tbody').append(row);
        });
    })
}

const resetUpdateHistoriesTable = () => {
    $('#update-histories-table').find('tr.data-row').each(function () {
            $(this).remove();
    });
}

const addDataToRow = (row, history) => {
    $('#update-histories-table th[scope="col"]').each(function () {
        const key = $(this).attr('data-value');
        row.append($(`<td> ${history[key]} </td>`));

    });
}
