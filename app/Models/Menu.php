<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_name',
        'title',
        'filter_name',
        'price',
        'description',
        'created_by',
        'updated_by'
    ];
    

    // public function departments()
    // {
    //     return $this->hasMany(Department::class,'branch_id','id');
    // }
}
