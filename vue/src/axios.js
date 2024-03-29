 import axios from "axios";
 import store from "./store";
 import router from "./router";
 
 const axiosClient = axios.create({
      baseURL: `http://127.0.0.1:8000/api`
 })
 
 axiosClient.interceptors.request.use(config => {
   config.headers.Authorization = `Bearer ${store.state.user.token}`
   return config;
 })
 
 axiosClient.interceptors.response.use((response) => {
   return response;
 }, function (error) {
   if (error.response.status === 401) {
     sessionStorage.removeItem('TOKEN')
     router.push({name: 'Login'})
   } else if (error.response.status === 404) {
     router.push({name: 'NotFound'})
   }
   return Promise.reject(error.response);
 })
 
 export default axiosClient;