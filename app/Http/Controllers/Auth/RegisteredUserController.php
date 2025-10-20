<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function addUser(): view {
        return view('admin.users.add_user');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'user_type' => ['required','in:admin,data_entry,financial_employee,student,teacher'],
                'status' => ['required','in:active,inactive'],
                'permission' => ['required'],
            ],
            [
                'password.min' => 'كلمة المرور ضعيفة يجب ان تحوي على الاقل 8 (احرف و ارقام)',
            ]);

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
                'status' => $request->status,
                'permission' => json_encode($request->permission),
            ]);
    
            event(new Registered($user));
    
            return back()->with('success','تم إنشاء مستخدم جديد');
    
            }catch(Exception $e){
                return back()->with('error',$e->getMessage())->withInput();
            }
    }
}
