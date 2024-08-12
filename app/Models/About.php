<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class About extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'abouts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_name',
        'title',
        'description',
        'image',
        'video_url',
        'created_by',
        'updated_by'
    ];
    

    // public function departments()
    // {
    //     return $this->hasMany(Department::class,'branch_id','id');
    // }
}
