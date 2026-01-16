<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Api\UserRepository;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ManageApiTrait  ;


    public function index(Request $request)
    {
        $users = User::with('orders')->filter($request)->latest()->paginate(15);
        return $this->successApi($users , 'Users fetched successfully ');
    }

    public function show(User $user)
    {
        return $this->successApi($user->load('orders') , 'User fetched successfully ');
    }

}
