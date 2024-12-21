import fetch from '@/utils/fetch'
import {product_baseUrl as baseUrl} from './api'

export function getInfo(searchObj = {}, page = 1, pageSize = 10,id) {
  return fetch({
    url: baseUrl,
    method: 'get',
    params: {
        id,
        page: page,
        pageSize: pageSize,

    }
  })
}

export function getInfoById (id) {
  return fetch({
    url: baseUrl + '/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {

    if (typeof data.buy_date === 'string') {
      data.buy_date = Math.ceil(Date.parse(data.buy_date) / 1000)
    } else {
      data.buy_date = Math.ceil(((data.buy_date).getTime()) / 1000)
    }
    return fetch({
      url: baseUrl + '/' + id,
      method: 'PATCH',
      data,
    })
}

export function deleteInfoById (id) {
  return fetch({
    url: baseUrl +'/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  console.log(typeof data.buy_date);

  data.buy_date =Math.ceil(((data.buy_date).getTime()) / 1000)
  return fetch({
    url: baseUrl,
    method: 'post',
    data,
  })
}

export function deleteAll( params) {
  return fetch({
     url: baseUrl + '/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })
}

// export function uploadFile(data) {
//   return fetch({
//     url: '/api/goods/upload',
//     method: 'post',
//     data,
//     headers: {
//       'Content-Type': 'multipart/form-data'
//     }
//   })
// }
//

// 获取订单对应的产品信息
