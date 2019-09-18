<?php

declare(strict_types=1);

namespace App\Controller;

use App\Lib\Passport;
use App\Model\User;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class UserController extends Controller
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $pagrams = $request->all();

        $res = User::with('role')->when(!empty($pagrams['name']),function ($q) use($pagrams){
            return $q->where('name','LIKE','%'.$pagrams['name'].'%');
        })->orderBy('created_at','asc')->paginate(15);

        return $response->json(['code'=>200,'data'=>$res]);
    }

    public function update(RequestInterface $request, ResponseInterface $response,$id){
        $pagrams = $request->all();
        $model = User::find($id);
        $model->name = $pagrams['name'];
        $model->nickname = $pagrams['nickname'];
        if(isset($pagrams['role'])){
            $model->role = $pagrams['role'];
        }
        $model->save();

        return $response->json(['code'=>200,'msg'=>'更新成功！']);
    }
    public function info(){
        $token = $this->request->input('token');

        $data = Passport::checkToken($token);

        return $this->response->json($data);
    }

    public function delete(int $id, ResponseInterface $response){
         if($id ==1){
             return $response->json(['code'=>403,'msg'=>'超级管理员禁止删除']);
         }
         User::where('id',$id)->delete();

         return $response->json(['code'=>200,'msg'=>'删除成功！']);
    }

    /**
     * @param $id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function disableSwitch($id){
        $user = User::where('id',$id)->first();
        $user->status = $user->status == 1 ? 0:1;
        $user->save();
        return $this->response->json(['code'=>200,'msg'=>'操作成功！']);
    }


}
