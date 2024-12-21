import fetch from '@/utils/fetch'

export function getInfo(searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/configs',
    method: 'get',
    params: {
        page,
        pageSize,
        sub_number:searchObj.sub_number
    }
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/configs/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {

  return fetch({
    url: '/api/configs/' + id,
    method: 'PATCH',
    data: {
      id: id,
      sub_number:data.sub_number,
      result: JSON.stringify(data.result),
      remark:data.remark,
    }
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/configs/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/configs',
    method: 'post',
    data:{
      sub_number:data.sub_number,
      result: JSON.stringify(data.result),
      remark:data.remark,
    }
  })
}

export function deleteAll( params) {
  return fetch({
     url: '/api/configs/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })
}


export function uploadFile(data) {
  return fetch({
    url: '/api/configs/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}


export function getConfigsInfo() {
  return fetch({
    url: '/api/configs/getAll',
    method: 'get',
  })
}
