<?php

declare(strict_types=1);

namespace App\Controller;

use App\Lib\Passport;
use App\Model\User;
use Defuse\Crypto\Crypto;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class AuthController
{

    public function login(RequestInterface $request,ResponseInterface $response){
        $params = $request->all();
        $user = User::where('name',$params['username'])->first();

        if ($user === null) {

            return $response->json(['code'=>500,'msg'=>'用户不存在']);

        }

        if(!password_verify ( $params['password'] , $user['password'])){

            return $response->json(['code'=>500,'msg'=>'密码输入不正确!!!!']);
        };

        $token = Passport::getToken([
            'id'=>$user->id,
            'name'=>$user->name,
            'nickname'=>$user->nickname,
            'roles'=>$user->role,
            'avatar'=>$user->avatar,
        ]);

        return $response->json(['code'=>200,'msg'=>'登陆成功！','token'=>$token]);

    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function register(RequestInterface $request,ResponseInterface $response){
        $params = $request->all();

        $avatar = new \LasseRafn\InitialAvatarGenerator\InitialAvatar();
        $image = $avatar->name($params['name'])->size(40)->generateSvg()->toXMLString();
        $data = [
            'avatar' => $image,
            'nickname' => $params['nickname'],
            'name' => $params['name'],
            'password' => password_hash($params['password'], PASSWORD_DEFAULT),
            'role'=>$params['role']
        ];

        $res = User::create($data);

        return $response->json(['code'=>200,'msg'=>'添加成功！']);
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function resetUserPassword(RequestInterface $request,ResponseInterface $response){
        $params = $request->all();

        $user = User::where('name',$params['name'])->first();

        if ($user === null) {

            return $response->json(['code'=>500,'msg'=>'用户不存在']);

        }

        if(!password_verify ( $params['oldpassword'] , $user['password'])){

            return $response->json(['code'=>500,'msg'=>'原密码输入不正确']);
        };

        $user->password = password_hash($params['password'],PASSWORD_DEFAULT);

        $user->save();

        return $response->json(['code'=>200,'msg'=>'密码重置成功！']);

    }

    public function logout(RequestInterface $request,ResponseInterface $response){
        return $response->json(['code'=>200,'msg'=>'退出成功！']);
    }

}
