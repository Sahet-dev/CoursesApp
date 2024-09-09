import axios from 'axios';

const axiosInstance = axios.create({
    baseURL: 'http://127.0.0.1:8000/api',
    timeout: 10000,
});

axiosInstance.interceptors.request.use(
    (config) => {

        const token = sessionStorage.getItem('token');


        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Export the configured Axios instance
export default axiosInstance;