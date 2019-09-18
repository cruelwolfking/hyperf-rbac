<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\Controller;
use App\Model\Roles;
use App\Model\User;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class RolesController extends Controller
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $data = Roles::with('permissions')->get()->toArray();

        return $response->json(['code'=>200,'msg'=>'获取成功','data'=>$data]);
    }

    public function store(){
        $pagrams = $this->request->all();

        $has = Roles::where('name',$pagrams['name'])->exists();

        if($has){
            return $this->response->json(['code'=>405,'data'=>'','msg'=>'角色已经存在']);
        }

        Db::beginTransaction();
        try{
            $id = Db::table('roles')->insertGetId([
                'title'=>$pagrams['title'],
                'name'=>$pagrams['name']
            ]);
            foreach ($pagrams['permissions'] as $v){
                Db::table('role_has_permissions')->insert(
                    [
                        'role_id'=>$id,
                        'permission_id'=>$v
                    ]
                );
            }
            Db::commit();
            return $this->response->json(['code'=>200,'data'=>'','msg'=>'创建成功！']);
        }catch (\Exception $exception){
            Db::rollBack();
        }

    }


    public function update(int $id){
        $pagrams = $this->request->all();

        Db::beginTransaction();
        try{
            //删除原来的

            Db::table('role_has_permissions')->where('role_id',$id)->delete();

             Db::table('roles')->where('id',$id)->update([
                'title'=>$pagrams['title'],
                'name'=>$pagrams['name']
            ]);
            foreach ($pagrams['permissions'] as $v){
                Db::table('role_has_permissions')->insert(
                    [
                        'role_id'=>$id,
                        'permission_id'=>$v
                    ]
                );
            }
            Db::commit();
            return $this->response->json(['code'=>200,'data'=>'','msg'=>'更新成功！']);
        }catch (\Exception $exception){
            Db::rollBack();
        }

        return $this->response->json(['code'=>200,'data'=>'','msg'=>'更新成功！']);
    }

    public function desdory(int  $id){
        Db::beginTransaction();
        try{

            $has = User::where('role',$id)->exists();
            if($has){
                Db::rollBack();
                return $this->response->json(['code'=>403,'data'=>'','msg'=>'还存在当前角色的管理员，不能删除！']);
            }

            Db::table('role_has_permissions')->where('role_id',$id)->delete();

            Roles::destroy($id);

            Db::commit();

            return $this->response->json(['code'=>200,'data'=>'','msg'=>'删除成功！']);
        }catch (\Exception $exception){
            Db::rollBack();
            return $this->response->json(['code'=>200,'data'=>'','msg'=>'删除失败！']);
        }
    }
}
