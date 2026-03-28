<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateIdentityRequest;
use App\Models\User;
use App\Services\ImageStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(private readonly ImageStorageService $imageStorageService)
    {
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'telephone' => $data['telephone'] ?? null,
            'cin' => $data['cin'],
            'numero_permis' => $data['numero_permis'],
            'password' => Hash::make($data['password']),
            'role' => 'client',
        ]);

        if ($request->hasFile('cin_document')) {
            $path = $this->imageStorageService->storeIdentityDocument($request->file('cin_document'), $user->id, 'cin');
            $user->update(['cin_document_path' => $path]);
        }

        if ($request->hasFile('permis_document')) {
            $path = $this->imageStorageService->storeIdentityDocument($request->file('permis_document'), $user->id, 'permis');
            $user->update(['permis_document_path' => $path]);
        }

        Auth::guard('web')->login($user);

        $redirectTo = $this->sanitizeRedirectPath($request->input('redirect_to'));

        return redirect($redirectTo ?? '/')->with('success', 'Bienvenue ' . $user->name . '!');
    }

    public function profile()
    {
        return view('auth.profile');
    }

    public function updateProfile(UpdateIdentityRequest $request)
    {
        $user = $request->user();
        $payload = $request->safe()->only(['telephone', 'adresse', 'cin', 'numero_permis']);

        if ($request->hasFile('cin_document')) {
            $this->imageStorageService->deletePrivateFile($user->cin_document_path);
            $payload['cin_document_path'] = $this->imageStorageService->storeIdentityDocument($request->file('cin_document'), $user->id, 'cin');
        }

        if ($request->hasFile('permis_document')) {
            $this->imageStorageService->deletePrivateFile($user->permis_document_path);
            $payload['permis_document_path'] = $this->imageStorageService->storeIdentityDocument($request->file('permis_document'), $user->id, 'permis');
        }

        $user->update($payload);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Vous êtes déconnecté.');
    }

    private function sanitizeRedirectPath(?string $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        if (! Str::startsWith($path, '/')) {
            return null;
        }

        if (Str::startsWith($path, '//')) {
            return null;
        }

        return $path;
    }
}
