/*
 * This is the initial API interface
 * we set the base URL for the API.
 * This will be used for making authenticated requests.
 */

import axios from "axios";

export const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    withCredentials: true, // required to handle the CSRF token
});

export const generateQueryString = (search, orderBy, orderDirection) => {
    const queryParams = [];

    if (search) {
        queryParams.push(`keyword=${search}`);
    }
    if (orderBy) {
        queryParams.push(`orderBy=${orderBy}`);
    }
    if (orderDirection) {
        queryParams.push(`order=${orderDirection}`);
    }

    return queryParams.length > 0 ? `&${queryParams.join('&')}` : '';
}