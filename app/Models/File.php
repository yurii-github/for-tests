<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $data
 * @property string $filename
 * @property string $mime
 * @property int $size
 * @property int $target_id
 * @property string $target_type
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class File extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'file';

    /**
     * @var array
     */
    protected $fillable = ['data', 'filename', 'mime', 'size', 'target_id', 'target_type', 'created_at', 'updated_at', 'deleted_at'];

}
