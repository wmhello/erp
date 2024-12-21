import fetch from '@/utils/fetch'

export function getInfo(searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/purchases',
    method: 'get',
    params: {
        page,
        pageSize,
        good_number: searchObj.good_number,
        date: searchObj.date,
        cate_id: searchObj.cate_id
    }
  })
}

export function uploadFile(data) {
  return fetch({
    url: '/api/purchases/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

export function getBaseInfo() {
  return fetch({
    url: '/api/purchases/getInfo',
    method: 'get'
  })
}

export function importStore(arr) {
  console.log(arr)
  return fetch({
     url: '/api/purchases/importStorage',
     method: 'post',
     data: {
       ids: arr
     }
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/purchases/' + id,
    method: 'get',
  })
}

export function updateInfo(id, data) {

  return fetch({
    url: '/api/purchases/' + id,
    method: 'PATCH',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/purchases/' + id,
    method: 'delete',
  })
}
