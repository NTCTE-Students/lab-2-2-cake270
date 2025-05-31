<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request): RedirectResponse
    {
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), 
        ]);

        return redirect()
            -> route('index');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()
                -> route('index');
        }

        return redirect()
            -> back();
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()
            -> route('index');
    }
    public function index(Request $request): View
    {
        if (Auth::check()) {

            $user_tasks = Auth::user() -> tasks();

            if ($request -> input('status_filter') && in_array($request -> input('status_filter'), ['in_progress', 'pending', 'complete'])) {
                $user_tasks -> where('status', $request -> input('status_filter'));
            }
            if ($request -> input('order_by')) {
                if ($request -> input('order_by') == 'date_desc') {
                    $user_tasks -> orderByDesc('created_at');
                }
            }
            if ($request -> input('find')) {
                $user_tasks -> where('title', $request ->input('find')) -> get();
            }

            return view('index', [
                'user_tasks' => $user_tasks,
            ]);
        } else {
            return view('index', [
                'user_tasks' => null,
            ]);
        }
    }
}
