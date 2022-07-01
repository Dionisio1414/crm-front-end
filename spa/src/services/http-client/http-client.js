import axios from 'axios';
import getAccessToken from '@/helper/getAccessToken';
import formationUrl from "@/helper/getBasedUrl";

const httpClient = axios.create();

const authInterceptor = (config) => {
  const token = getAccessToken();
  const baseURL = formationUrl();
  const updated = { ...config, baseURL };
  updated.headers['Content-Type'] = 'application/json';
  updated.headers['Authorization'] = `Bearer ${token}`;
  updated.headers['Cache-Control'] = 'no-cache'
  return updated;
};

httpClient.interceptors.request.use(authInterceptor);

export default httpClient;