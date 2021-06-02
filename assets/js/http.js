import axios from 'axios'

let http = axios.create({
    baseURL: '/api',
    headers: {'Content-Type': 'application/json'},
});

export default http