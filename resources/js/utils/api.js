import ky from 'ky';

const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

const client = ky.create({
    headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

const dispatchLoadingStart = () => window.dispatchEvent(new Event('loading-start'));
const dispatchLoadingEnd = () => window.dispatchEvent(new Event('loading-end'));

async function parseResponse(response) {
    const contentType = response.headers.get('content-type') || '';
    return contentType.includes('application/json') ? response.json() : response.text();
}

async function request(method, url, options = {}) {
    dispatchLoadingStart();

    try {
        return await client(url, {
            method,
            ...options,
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                ...options.headers,
            },
        }).then(parseResponse);
    } catch (error) {
        if (error.response) {
            const data = await error.response.clone().json().catch(() => ({}));
            error.status = error.response.status;
            error.data = data;
            error.errors = data.errors;
            error.message = data.message || error.message;
        }

        throw error;
    } finally {
        dispatchLoadingEnd();
    }
}

const bodyOptions = (data) => data instanceof FormData
    ? { body: data }
    : { json: data };

export const api = {
    get: (url, params = {}) => request('GET', url, { searchParams: params }),
    post: (url, data = {}) => request('POST', url, bodyOptions(data)),
    put: (url, data = {}) => request('PUT', url, bodyOptions(data)),
    patch: (url, data = {}) => request('PATCH', url, bodyOptions(data)),
    delete: (url) => request('DELETE', url),
};

// Helper for Laravel's form method spoofing.
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
    tokenInput.value = csrfToken();

    form.append(methodInput, tokenInput);
    document.body.appendChild(form);
    form.submit();
    form.remove();
};
