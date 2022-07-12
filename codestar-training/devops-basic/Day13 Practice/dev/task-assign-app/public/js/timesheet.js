const initializeProcessMethod = () => {
    const assigneeId = $('#assignee_id').val();
    const jobId = $('#job_id').val();
    getProcessMethod(jobId, assigneeId).then(processMethod => {
        $('#process_method').val(processMethod?.name);
    });
}