<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $hidden = ['created_at' , 'updated_at'] ;



    public static function createWithPermission($request)
    {
        try{
            DB::beginTransaction();
            $role = Role::create(['name' => $request->name ]);

            foreach($request->permissions as $permission => $value){
                RolePermission::create([
                    'role_id' => $role->id ,
                    'permission' => $permission ,
                    'authorize' => $value // allow or deny
                ]);
            }
            DB::commit();
            return $role ;
        }catch(\Exception $e){
            DB::rollBack() ;
            throw $e ;
        }
    }

    public static function updateWithPermission($request , $role )
    {
        try{
            DB::beginTransaction();
            $role->update(['name' => $request->name]);

            foreach($request->permissions as $permission => $value){
                RolePermission::updateOrCreate([ // n => query
                    'role_id' => $role->id ,
                    'permission' => $permission ,
                ],[
                    'authorize' => $value // allow or deny
                ]);
            }
            /* Upsert 1 (query) */ 
            DB::commit();
            return $role->load('permissions') ;
        }catch(\Exception $e){
            DB::rollBack() ;
            throw $e ;
        }
    }




    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }



}
