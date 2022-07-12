const getJob = (id, options) => {
    return fetch(`/api/jobs/${id}`, {
        method: "GET",
        ...options
    }).then(response => {
        return response.json();
    }).catch(err => console.log(err));
} 

const getUpdateHistories = (id, options) => {
    return fetch(`/api/update-job-histories?job-id=${id}`, {
        method: "GET",
        ...options
    }).then(response => response.json());
} 


const  getAssigneeList = (jobId, options) => {
    return fetch(`/api/assignee-list/${jobId}`, {
        method: "GET",
        ...options
    }).then(response => response.json());
}

const getWorkPlans = (jobId, assigneeId=null, options={}) => {
    const url = assigneeId ? `/api/workplans/${jobId}/${assigneeId}` : `/api/workplans/${jobId}`;
    return fetch(url, {
        method: "GET",
        ...options
    }).then(response => response.json());
}


const getJobAssigns = (assigneeId, jobIds, options) => {
    let url = `/api/job-assigns?staffId=${assigneeId}&`;

    jobIds.forEach(jobId => {
        url += `jobIds[]=${jobId}&`
    });

    url = url.slice(0, -1);

    return fetch(url, {
        method: "GET",
        ...options
    }).then(response => response.json());
}