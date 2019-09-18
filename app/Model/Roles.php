<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\MorphMany;
use Hyperf\DbConnection\Model\Model;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string $guard_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Roles extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';
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
    protected $fillable = ['id', 'title', 'name', 'guard_name', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 获取所有角色
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function roles(ResponseInterface $response){
        $data = self::all()->toArray();

        return $response->json(['code'=>200,'data'=>$data]);
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permissions::class,
            'role_has_permissions',
            'role_id',
            'permission_id'
        );
    }




}