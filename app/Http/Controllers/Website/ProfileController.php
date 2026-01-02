<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\ProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()
    {
        return view('pages.website.auth.profile') ;
    }
    public function update(ProfileRequest $request)
    {
        $user = User::findorfail(auth()->user()->id ) ;
        $validated = $request->validated();
        $validated['password'] = Hash::make($request->password) ;
        $user->update($validated);
        return redirect()->back()->with('success' , 'تم تحديث بياناتك بنجاح') ;
    }
}
