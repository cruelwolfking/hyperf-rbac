import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/login',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: '/admin/user/info',
    method: 'get',
    params: { token }
  })
}

export function logout() {
  return request({
    url: '/admin/logout',
    method: 'post'
  })
}


export function index(pagrams) {
  return request({
    url: '/admin/user/index',
    method: 'get',
    params:pagrams
  })
}

export function store(data) {
  return request({
    url: '/admin/user/store',
    method: 'post',
    data
  })
}

export function update(data,id) {
  return request({
    url: '/admin/user/update/' + id,
    method: 'post',
    data
  })
}

export function destroy(id) {
  return request({
    url: '/admin/user/delete/' + id,
    method: 'get',
  })
}

export function disableSwitch(data) {
  return request({
    url: '/admin/user/switch/'+data,
    method: 'get',
  })
}


export function resetUserPassword(data) {
  return request({
    url: '/admin/resetUserPassword' ,
    method: 'post',
    data
  })
}

