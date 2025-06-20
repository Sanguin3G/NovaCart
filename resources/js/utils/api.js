// Get CSRF token from meta tag
const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

// Common headers for JSON requests
const jsonHeaders = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': getCsrfToken()
};

// Handle response and errors
const handleResponse = async (response) => {
    const data = await response.json();
    if (!response.ok) {
        const error = new Error(data.message || 'Something went wrong');
        error.status = response.status;
        error.data = data;
        if (data.errors) {
            error.errors = data.errors;
        }
        throw error;
    }
    return data;
};

// Loading state dispatchers
const dispatchLoadingStart = () => window.dispatchEvent(new Event('loading-start'));
const dispatchLoadingEnd = () => window.dispatchEvent(new Event('loading-end'));

// API methods
export const api = {
    // GET request
    get: async (url, params = {}) => {
        dispatchLoadingStart();
        try {
            const query = new URLSearchParams(params);
            const response = await fetch(`${url}?${query}`, {
                headers: {
                    ...jsonHeaders,
                    'Content-Type': undefined // Let the browser set it
                }
            });
            return await handleResponse(response);
        } finally {
            dispatchLoadingEnd();
        }
    },

    // POST request
    post: async (url, data) => {
        dispatchLoadingStart();
        try {
            const isFormData = data instanceof FormData;
            const response = await fetch(url, {
                method: 'POST',
                body: isFormData ? data : JSON.stringify(data),
                headers: isFormData 
                    ? { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': getCsrfToken() }
                    : jsonHeaders
            });
            return await handleResponse(response);
        } finally {
            dispatchLoadingEnd();
        }
    },

    // PUT request
    put: async (url, data) => {
        dispatchLoadingStart();
        try {
            const isFormData = data instanceof FormData;
            const response = await fetch(url, {
                method: 'PUT',
                body: isFormData ? data : JSON.stringify(data),
                headers: isFormData 
                    ? { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': getCsrfToken() }
                    : jsonHeaders
            });
            return await handleResponse(response);
        } finally {
            dispatchLoadingEnd();
        }
    },

    // PATCH request
    patch: async (url, data = {}) => {
        dispatchLoadingStart();
        try {
            const response = await fetch(url, {
                method: 'PATCH',
                body: JSON.stringify(data),
                headers: jsonHeaders
            });
            return await handleResponse(response);
        } finally {
            dispatchLoadingEnd();
        }
    },

    // DELETE request
    delete: async (url) => {
        dispatchLoadingStart();
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: jsonHeaders
            });
            return await handleResponse(response);
        } finally {
            dispatchLoadingEnd();
        }
    }
};

// Helper for Laravel's form method spoofing
export const formWithMethod = (method, url) => {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = method;
    
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = getCsrfToken();
    
    form.appendChild(methodInput);
    form.appendChild(tokenInput);
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
};

