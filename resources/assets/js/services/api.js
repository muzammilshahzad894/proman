/*
 * This is the initial API interface
 * we set the base URL for the API.
 * This will be used for making authenticated requests.
 */

import axios from "axios";
import { useAuthStore } from "../store/auth"

export const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    withCredentials: true, // required to handle the CSRF token
});

/*
 * Add a response interceptor
 */
apiClient.interceptors.response.use(
    (response) => {
        return response;
    },
    function (error) {
        const store = useAuthStore()

        if (error.response && [401, 419].includes(error.response.status) && store.user) {
            store.logout()
        }
        return Promise.reject(error);
    }
);
