const queryAmountConfirm = (params, options) => {
    
    return fetch('/api/amount-confirms', {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: params,
        ...options
    })
    .then(response => {
        if (response.status === 200) {
            return response.json();
        }
        else {
            return null;
        }
        
    })
    .catch(err => console.log(err));
} 
