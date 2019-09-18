import request from '@/utils/request'


export function getRoles() {
  return request({
    url: '/admin/role',
    method: 'get'
  })
}

export function addRole(data) {
  return request({
    url: '/admin/role/store',
    method: 'post',
    data
  })
}

export function updateRole(data, id) {
  return request({
    url: `/admin/role/update/${id}`,
    method: 'post',
    data
  })
}

export function deleteRole(id) {
  return request({
    url: `/admin/role/${id}`,
    method: 'get'
  })
}
