<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id
 * @property string $original_name
 * @property string $filename
 * @property string $ext
 * @property float $size
 * @property string $path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Files extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'files';
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
    protected $fillable = ['id', 'original_name', 'filename', 'ext', 'size', 'path', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'size' => 'float', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}