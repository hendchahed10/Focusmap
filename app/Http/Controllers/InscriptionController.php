<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class InscriptionController extends Controller
{
    public function showForm()
    {
        return view('inscription'); 
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'login' => 'required|string|max:255|unique:utilisateurs,login',
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                ],
            ]);

            $user = Utilisateur::create([
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'login' => $validated['login'],
                'password' => Hash::make($validated['password']),
            ]);

            Auth::login($user);

            return redirect()->intended('dashboard');

        } catch (ValidationException $e) {
            if ($e->errors()['login'] ?? false) {
                return back()
                    ->withErrors(['login' => 'Ce login existe déjà !'])
                    ->withInput($request->except('password'));
            }

            return back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }
}
