import fetch from '@/utils/fetch'

export function getInfo(searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/params',
    method: 'get',
    params: {
        page,
        pageSize,
    }
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/params/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {

  return fetch({
    url: '/api/params/' + id,
    method: 'PATCH',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/params/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/params',
    method: 'post',
    data,
  })
}

export function deleteAll( params) {
  return fetch({
     url: '/api/params/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })
}
