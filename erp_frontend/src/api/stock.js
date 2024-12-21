import fetch from '@/utils/fetch'

export function getInfo(searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/stocks',
    method: 'get',
    params: {
        page,
        pageSize,
        good_number: searchObj.good_number,
        cate_id: searchObj.cate_id
    }
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/stocks/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {

  return fetch({
    url: '/api/stocks/' + id,
    method: 'PATCH',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/stocks/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/stocks',
    method: 'post',
    data,
  })
}

export function deleteAll( params) {
  return fetch({
     url: '/api/stocks/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })
}

export function uploadFile(data) {
  return fetch({
    url: '/api/stocks/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

export function exportFile(data) {
  return fetch({
    url: '/api/stocks/export',
    method: 'post',
  })
}
