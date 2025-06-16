<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required|min:6',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
        ]);

        if (!$request->email && !$request->phone) {
            return back()->withErrors(['identifier' => 'Informe um e-mail ou telefone.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'password' => 'required'
        ]);

        $user = $this->userRepo->findByIdentifier($request->identifier);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['login' => 'Credenciais inválidas']);
        }

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}