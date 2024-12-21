import fetch from '@/utils/fetch'
import {package_baseUrl as baseUrl} from './api'

export function getInfo(searchObj = {}, page = 1, pageSize = 10, id) {
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

    if (typeof data.order_time === 'string') {
      data.order_time = Math.ceil(Date.parse(data.order_time)/1000)
    } else {
      data.order_time =Math.ceil(((data.order_time).getTime())/1000)
    }
    return fetch({
      url: baseUrl +'/' + id,
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
  data.logistics_date =Math.ceil(((data.logistics_date).getTime())/1000)
  return fetch({
    url: baseUrl,
    method: 'post',
    data:JSON.stringify(data),
    headers: {
          'Content-Type': 'application/json'
    },
  })
}

export function addRemark (id,remark) {
  return fetch({
    url: baseUrl + '/' + id + '/remark',
    method: 'post',
    data: {
      remark
    },
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


// 获取订单对应的包裹信息

export function getPackagesByOrderId(id) {
  return fetch({
    url: '/api/orders/' + id + '/packages',
    method: 'get',
  })
}
export function getPackageInfoById(id) {
  return fetch({
    url: '/api/packages/' + id + '/info',
    method: 'get',
  })
}
