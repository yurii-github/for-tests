<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int $id
 * @property int $form_id
 * @property Form $form
 */
class FormItem extends Model
{
    protected $table = 'form_item';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @return MorphOne
     */
    public function file()
    {
        return $this->morphOne(File::class, 'target', 'target_type', 'target_id');
    }
}
