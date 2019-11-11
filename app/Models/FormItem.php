<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $form_id
 * @property Form $form
 */
class FormItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'form_item';

    /**
     * @var array
     */
    protected $fillable = ['form_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
