import fetch from '@/utils/fetch'

export function getInfo(searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/storages',
    method: 'get',
    params: {
        page,
        pageSize,
        date: searchObj.date,
        number:searchObj.good_number
    }
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/storages/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {
  return fetch({
    url: '/api/storages/' + id,
    method: 'PATCH',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/storages/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/storages',
    method: 'post',
    data,
  })
}

export function deleteAll( params) {
  return fetch({
     url: '/api/storages/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })
}

export function uploadFile(data) {
  return fetch({
    url: '/api/storages/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}
