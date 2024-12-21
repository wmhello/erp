import fetch from '@/utils/fetch'

export function getInfo(searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/goods',
    method: 'get',
    params: {
        page: page,
        pageSize: pageSize,
        number: searchObj.number,
        cate_id:searchObj.cate_id
    }
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/goods/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {

  return fetch({
    url: '/api/goods/' + id,
    method: 'PATCH',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/goods/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/goods',
    method: 'post',
    data,
  })
}

export function deleteAll( params) {
  return fetch({
     url: '/api/goods/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })
}

export function uploadFile(data) {
  return fetch({
    url: '/api/goods/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}
// 获取信息给配对表使用
export function getGoodsInfo () {
  return fetch({
    url: '/api/goods/getInfo',
    method: 'get',
  })
}
