<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title' , 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->withDefault(['name' => 'Primary Category']);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class, 'category_id');
    }
}
