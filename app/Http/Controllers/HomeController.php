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
        dd($id);
        $user = User::find($id);
        $user->delete();
        return redirect('pages.roles');
    }

    public function update(Request $request)
    {
        $users = User::all();
        echo("hello");
    }
}
