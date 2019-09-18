<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property string $title
 * @property string $guard_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Permissions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'default';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'pid', 'name', 'title', 'guard_name', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    protected $list=[];

    public   function getList(){
        $data = self::all()->toArray();

        return $this->tree($data,0);

    }

    public  function tree($array, $pid)
    {
        $tree = array();
        foreach ($array as $key => $value) {
            if ($value['pid'] == $pid) {
                $value['child'] = self::tree($array, $value['id']);
                if (!$value['child']) {
                    unset($value['child']);
                }
                $tree[] = $value;
            }
        }
        return $tree;
    }

    public   function lists(){

        $data = self::all()->toArray();
        return $this->getTree($data,0);
    }

    public function getTree($array, $pid =0, $level = 0){

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖

        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $this->list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                self::getTree($array, $value['id'], $level+1);

            }
        }
        return $this->list;


    }

}