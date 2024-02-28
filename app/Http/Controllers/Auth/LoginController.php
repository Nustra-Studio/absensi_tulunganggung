<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {   
        $input = $request->all();
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
        ]);
        if(auth()->attempt(array('name' => $input['name'], 'password' => $input['password'])))
        {
            // if (auth()->user()->type == 'super-admin') {
            //     return redirect()->route('super.admin.dashboard');
            // }else if (auth()->user()->type == 'manager') {
            //     return redirect()->route('manager.dashboard');
            // }else{
            //     return redirect()->route('dashboard');
            // }
        return redirect('/database/cabang');
        }else{
            return redirect()->route('login')
                ->with('error','username And Password Are Wrong.');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function showLoginForm(){
        return view('pages.auth.login');
    }
}