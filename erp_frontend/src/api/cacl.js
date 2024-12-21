import fetch from '@/utils/fetch'

export function getInfo(searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/cacls',
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
    url: '/api/cacls/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {
  return fetch({
    url: '/api/cacls/' + id,
    method: 'PATCH',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/cacls/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/cacls',
    method: 'post',
    data,
  })
}

export function deleteAll( params) {
  return fetch({
     url: '/api/cacls/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })
}
export function exportFile( ) {
  return fetch({
     url: '/api/cacls/export',
     method: 'post',
  })
}
