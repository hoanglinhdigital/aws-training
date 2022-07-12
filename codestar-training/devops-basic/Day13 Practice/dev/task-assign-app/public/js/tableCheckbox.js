const handleHeadCheckboxClick = (headSelector, bodySelector) => {
    $(headSelector).change(function() {
        $(bodySelector).prop('checked', this.checked).change();
    })
}

const handleBodyCheckboxClick = (headSelector, bodySelector, callback) => {
    $(bodySelector).change(function() {
        callback(headSelector, bodySelector, this);
    })
    
}