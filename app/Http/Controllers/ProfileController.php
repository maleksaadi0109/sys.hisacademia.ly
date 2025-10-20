<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Validation\Rules;


class ProfileController extends Controller
{

    public function users($orderBy , $sort): View
    {
        if($orderBy == 'null' || $sort == 'null') {
            $users =  User::where("user_type","!=","student")->where("user_type","!=","teacher")->paginate(8);
        }else{
            $users = User::where("user_type","!=","student")->where("user_type","!=","teacher")->orderBy($orderBy, $sort)->paginate(8);
        }
        return view('admin.users.users', [
            'users' => $users,
        ]);
    }

    public function profileUser($id)
    {
        return view('admin.users.profile_user', [
            'user' => User::find($id),
        ]);
    }
    
    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        try{
            
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'user_type' => ['required','in:admin,data_entry,financial_employee,student,teacher'],
                'status' => ['required','in:active,inactive'],
                'permission' => ['required'],
            ]);

            if($request->password != null){
                $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]);
                $user->password = Hash::make($request->password);
            }
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->user_type = $request->user_type;
            $user->status = $request->status;
            $user->permission = json_encode($request->permission);

            $user->save();
            return back()->with('success','تم تعديل البيانات بنجاح');
    
            }catch(Exception $e){
                return back()->with('error','هناك خطأ في البيانات يرجى ادخال بيانات صحيحة');
            }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
