import axios from 'axios'

const concatUrl = function(url) {
    const baseUrl = window.Laravel.url + '/api/'

    return baseUrl.concat(url)
}

export function get(url) {
    return axios({
    	method: 'GET',
    	url: concatUrl(url)
    })
}

export function post(url, payload = '') {
    return axios({
    	method: 'POST',
    	url: concatUrl(url),
    	data: payload
    })
}

export function del(url) {
    return axios({
        method: 'DELETE',
        url: concatUrl(url)
    })
}

export function patch(url, payload = '') {
    return axios({
        method: 'PATCH',
        url: concatUrl(url),
        data: payload
    })
}
