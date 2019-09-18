<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\Controller;
use App\Model\Permissions;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class PermissionsController extends Controller
{

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $model = new Permissions();
        $res = $model->getList();

        return $response->json(['code'=>200,'data'=>$res]);
    }

    public function store(RequestInterface $request ,ResponseInterface $response){
        $exit = Permissions::where('name',$request->input('name'))->exists();

        if($exit){
            return $response->json(['code'=>405,'data'=>'','msg'=>'权限已经存在']);
        }
        $permission = new  Permissions();
        $permission->pid = $request->input('pid');
        $permission->name = $request->input('name');
        $permission->title = $request->input('title');
        $permission->save();

        return $response->json(['code'=>200,'data'=>'','msg'=>'创建成功！']);
    }

    public function update(RequestInterface $request, ResponseInterface $response,$id){

        $exit = Db::table('permissions')->where('id','!=',$id)->where('name',$request->input('name'))->exists();

        if($exit){
            return $response->json(['code'=>405,'data'=>'','msg'=>'权限已经存在']);
        }
        $permission = Permissions::where('id',$id)->first();
        $permission->name = $request->input('name');
        $permission->title = $request->input('title');
        $permission->save();

        return $response->json(['code'=>200,'data'=>'','msg'=>'更新成功！']);
    }

    public function desdory(int $id, ResponseInterface $response){
        //先去掉对应角色拥有这个权限的关系

        Db::beginTransaction();
        try{
            //先判断下级是否还有数据
            $has = Permissions::where('pid',$id)->exists();
            if($has){
                Db::rollBack();
                return $response->json(['code'=>403,'data'=>'','msg'=>'下级存在数据，禁止删除！']);
            }
            Db::table('role_has_permissions')->where('permission_id',$id)->delete();
            Permissions::destroy($id);
            Db::commit();
            return $response->json(['code'=>200,'data'=>'','msg'=>'删除成功！']);
        }catch (\Exception $exception){
            Db::rollBack();
            return $response->json(['code'=>200,'data'=>'','msg'=>'删除失败！']);
        }
    }

    /**
     * @param int $id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function drap(){
        $permission = Permissions::where('id',$this->request->input('id'))->first();
        $permission->pid = $this->request->input('pid');
        $permission->save();
        return $this->response->json(['code'=>200,'data'=>'','msg'=>'更新成功！']);
    }

    /**
     * 递归显示所有权限
     */
    public function getOptions(){

        $model = new Permissions();
        $res = $model->lists();
        return $this->response->json(['code'=>200,'data'=>$res]);
    }
}
