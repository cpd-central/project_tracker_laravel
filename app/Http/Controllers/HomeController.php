<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }

    public function edit_roles()
    {
        $users = User::all();
        return view('pages.roles', compact('users'));
    }

    public function destroy($id)
    {
        if(isset($id)){
            $user = User::find($id);
            $user->delete();
        }
        return redirect('/home');
    }

    public function update(Request $request)
    {
        $users = User::all();
        foreach($users as $user){
            if($user['role'] == 'sudo'){
                continue;
            }
            $stringSplit = explode(".", $user['email']);
            $string = $stringSplit[0]."_".$stringSplit[1];
            $user['role'] = $request[$string];
            $user->save();
        }
        return redirect('/home');
    }

    public function edit_account($id)
    {
        $user = User::find($id);
        return view('pages.editaccount', compact('user'));
    }
}
