import axios from 'axios';

const API_URL = 'https://api.example.com';

export const fetchData = () => axios.get(`${API_URL}/data`);
export const createData = (data) => axios.post(`${API_URL}/data`, data);
export const updateData = (id, data) => axios.put(`${API_URL}/data/${id}`, data);
export const deleteData = (id) => axios.delete(`${API_URL}/data/${id}`);
