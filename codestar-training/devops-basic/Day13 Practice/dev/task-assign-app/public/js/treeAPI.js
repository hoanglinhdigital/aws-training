/**
 * Get department List with Staff inside
 *
 * @param {*} options
 */
const getDepartmentList = options => {
    return fetch("/api/department", {
        method: "GET",
        ...options
    }).then(response => response.json());
};
