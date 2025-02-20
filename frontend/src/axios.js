import axios from 'axios';
import router from '@/router';

const axiosInstance = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  }
});

// toekns
axiosInstance.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.data.message === "Token inválido o expirado") {
      localStorage.removeItem("token");
      localStorage.removeItem("user_id");
      router.push("/login"); 
    }
    return Promise.reject(error);
  }
);

export default axiosInstance;
