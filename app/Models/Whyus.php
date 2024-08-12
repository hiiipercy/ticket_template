<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Whyus extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'whyuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_name',
        'title',
        'subtitle',
        'list_1',
        'list_title_1',
        'list_description_1',
        'list_2',
        'list_title_2',
        'list_description_2',
        'list_3',
        'list_title_3',
        'list_description_3',
        'created_by',
        'updated_by'
    ];
    

    // public function departments()
    // {
    //     return $this->hasMany(Department::class,'branch_id','id');
    // }
}
