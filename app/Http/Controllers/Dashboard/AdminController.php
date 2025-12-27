<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AdminRequest;
use App\Models\Admin;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use ManageApiTrait ;
    public function index()
    {
        $admins = Admin::with('role')->paginate(10) ;
        return $this->successApi($admins , 'Admins fetched successfully') ;

    }


    public function store(AdminRequest $request)
    {
        $validated = $request->validated() ;
        $validated['password'] = Hash::make($validated['password']);
        $validated['image'] = 'personal.jpg';
        $admin = Admin::create($validated);
        return $this->createApi($admin , 'Admin created successfully') ;
    }

    public function show(Admin $admin)
    {
        return $this->successApi($admin , 'Admin fetched successfully') ;
    }


    public function update(AdminRequest $request, Admin $admin)
    {
        $validated = $request->validated() ;
        $validated['password'] = Hash::make($request->password); // override
        $admin->update($validated) ;
        return $this->successApi($admin , 'Admin updated successfully') ;
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return $this->successApi(null , 'Admin delete successfully') ;
    }
}
