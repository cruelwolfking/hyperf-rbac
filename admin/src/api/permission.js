import request from '@/utils/request'

export function getData() {
  return request({
    url: '/admin/permissions',
    method: 'get',
  })
}


export function getOptions() {
  return request({
    url: '/admin/permission/options',
    method: 'get',
  })
}


export function createData(data) {
  return request({
    url: '/admin/permissions/store',
    method: 'post',
    data
  })
}

export function updateData(data) {
  return request({
    url: '/admin/permissions/update/' +data.id,
    method: 'post',
    data
  })
}

export function changePid(data) {
  return request({
    url: '/admin/permissions/drap' ,
    method: 'post',
    data
  })
}

export function deleteData(id) {
  return request({
    url: '/admin/permissions/' +id,
    method: 'get',
  })
}

