'use strict'

export async function createData(url = '', data = {}) {
    return await axios.post(url, data)
}

export async function deleteData(url = '', data = {}) {
    return await axios.delete(url, data)
}

export async function updateData(url = '', id, data = {}) {
    return await axios.post(url, id, data)
}
export async function showData(url, id) {
    return await axios.get(url, id)
}

export async function getResult(url,params) {
    return await axios.get(url,{params: params})
}

export default {
    createData,
    deleteData,
    updateData,
    showData,
    getResult
}
