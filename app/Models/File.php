<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $data
 * @property string $filename
 * @property string $mime
 * @property int $size
 * @property int $target_id
 * @property string $target_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read string $url
 */
class File extends Model
{
    use HasTimestamps;
    use SoftDeletes;

    protected $table = 'file';
    protected $fillable = ['data', 'filename', 'mime', 'size', 'target_id', 'target_type',];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    protected $hidden = [
      'data'
    ];
    protected $appends = [
        'url'
    ];

    public function getUrlAttribute()
    {
        return route('file.get', ['file' => $this->id]);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function target()
    {
        return $this->morphTo('target', 'target_type', 'target_id');
    }
}
