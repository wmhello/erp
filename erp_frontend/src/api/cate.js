import fetch from '@/utils/fetch'

export function getInfo(searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/cates',
    method: 'get',
    params: {
        page,
        pageSize
    }
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/cates/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {

  return fetch({
    url: '/api/cates/' + id,
    method: 'PATCH',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/cates/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/cates',
    method: 'post',
    data,
  })
}
export function uploadFile(data) {
  return fetch({
    url: '/api/cates/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

export function deleteAll( params) {
  return fetch({
     url: '/api/cates/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })
}

export function getAll () {
  return fetch({
    url: '/api/cates/getAll',
    method: 'get',
  })
}
