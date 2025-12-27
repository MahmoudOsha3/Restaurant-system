<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\RoleRequest;
use App\Models\Role;
use App\Models\RolePermission;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    use ManageApiTrait ;
    public function index()
    {
        $data['roles'] = Role::with('permissions')->get() ;
        $data['permissions'] = config('permission') ;
        return $this->successApi($data , 'Roles fetched successfully') ;
    }

    public function store(RoleRequest $request)
    {
        $role = Role::createWithPermission($request) ;
        return $this->createApi($role , 'Role created successfully') ;
    }


    public function show(Role $role)
    {
        $data = ['role' => $role->name , 'permission' => $role->permissions ] ;
        return $this->successApi($data , 'Role fetched successfully') ;
    }


    public function update(RoleRequest $request, Role $role)
    {
        $role = Role::updateWithPermission($request , $role) ;
        return $this->successApi($role , 'Role updated successfully') ;
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return $this->successApi(null , 'Role delete successfully') ;
    }

    public function getRoles()
    {
        $roles = Role::all() ;
        return $this->successApi($roles , 'Roles fetched successfully ') ;
    }
}
