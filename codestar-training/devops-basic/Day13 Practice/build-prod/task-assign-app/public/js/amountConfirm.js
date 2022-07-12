const initializeMonthValue = () => {
    const today = new Date(Date.now());
    
    const year = today.getFullYear();
    let month = today.getMonth() + 1;

    month = month < 10 ? `0${month}` : `${month}`;

    $('#month').val(`${year}-${month}`);
}

const initializeInputValues = (data) => {

    if (!('id' in data)) {
        
        resetInputValues();

        $('#old_confirm_amount').val(data.old_confirm_amount);
        $('#old_confirm_percentage').val(data.old_confirm_percentage);
        $('#request_amount').val(data.request_amount);
        $('#request_percentage').val(data.request_percentage);
    
    }
    else {

        $('#month').val(data.month.split('-').slice(0, -1).join('-'));
        $('#old_confirm_amount').val(data.old_confirm_amount);
        $('#old_confirm_percentage').val(data.old_confirm_percentage);
        $('#request_amount').val(data.request_amount);
        $('#request_percentage').val(data.request_percentage);
        $('#amount_confirm_id').val(data.id);
        $(`#assignee option[value="${data.job_assign.staff_id}"]`).prop('selected', true);
        $('#confirm_amount').val(data.confirm_amount).change();
        $(`#quality option[value="${data.quality}"]`).prop('selected', true);
        $('#note').val(data.note);
        
    }

}

const resetInputValues = () => {
    $('#amount_confirm_id').val(null);
    $('#confirm_amount').val(null).change();
    $(`#quality`).prop('selectedIndex', 0);
    $('#note').val(null);
}