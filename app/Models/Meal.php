<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Meal extends Model
{
    use HasFactory;
    protected $fillable = ['title' , 'description', 'price' , 'compare_price' , 'image' , 'preparation_time' , 'category_id' , 'status' ];
    protected $hidden = ['created_at' , 'updated_at' , 'image'];
    protected $appends = ['image_url'];

    // local scope
    public function scopeFilter(Builder $builder , $request)
    {
        $builder->when($request->title , function($builder , $title){
            $builder->where('title' ,'LIKE' , "%{$title}%") ;
        });
    }

    // Accessor
    public function getImageUrlAttribute()
    {
        return asset(Storage::url('meals/' . $this->image)) ;
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id') ;
    }
}
