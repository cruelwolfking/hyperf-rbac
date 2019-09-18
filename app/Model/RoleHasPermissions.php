<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class RoleHasPermissions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_has_permissions';
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
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];


    public function permission(){
        return $this->belongsTo(Permissions::class,'id','permission_id');
    }
}