const getProcessMethod = (jobId, staffId, options) => {
    return fetch(`/api/process-methods?job_id=${jobId}&staff_id=${staffId}`, {
        method: "GET",
        ...options
    }).then(response => {
        if (response.status === 200) {
            return response.json()
        }
        return null;
    });
} 