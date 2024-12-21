import fetch from '@/utils/fetch'

export function getInfo(searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/sells',
    method: 'get',
    params: {
        page,
        pageSize,
        good_number:searchObj.good_number,
        date: searchObj.date
    }
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/sells/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {

  return fetch({
    url: '/api/sells/' + id,
    method: 'PATCH',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/sells/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/sells',
    method: 'post',
    data,
  })
}

export function deleteAll( params) {
  return fetch({
     url: '/api/sells/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })
}

export function uploadFile(data) {
  return fetch({
    url: '/api/sells/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}
