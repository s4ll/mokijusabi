<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $users = User::latest()->paginate(10);
        $search = $request->search;

        $users = User::when($search, fn($query) =>
            $query->where(fn($q) =>
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('role', 'like', "%$search%")
            )
        )->latest()->paginate(10)->withQueryString();

        $roles = ['admin', 'employee'];
        return view('pages.user', compact('users', 'roles'));
    }

    public function login()
    {
        $users = User::all();
        return view('pages.auth.login', compact('users'));
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        try {
            $user = $request->only(['email', 'password']);
    
            if (Auth::attempt($user)) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->back()->with('failed', 'Proses login gagal. Cek email dan password.');
            }
        } catch (\Exception $error) {
            return redirect()->back()->with('failed', 'Terjadi kesalahan: ' . $error->getMessage());
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('logout', 'anda berhasil logout.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = ['admin', 'employee'];
        return view('components.user.create-modal', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:4',
            'role' => 'required|in:admin,employee',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password) ,
                'role' => $request->role,
            ]);

            return redirect()->route('user.index')->with('success', 'User created successfully!');
        } catch (\Exception $error) {
            return redirect()->route('user.index')->with('error', 'failed to create user.' . $error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $role = ['admin', 'employee'];
        return view('components.user.update-modal', compact('user', 'role'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'nullable|string|',
            'email' => ['nullable','string','email',Rule::unique('users')->ignore($id)],
            'password' => 'nullable|min:4',
            'role' => 'nullable|in:admin,employee',
        ]);

        $user = User::findOrFail($id);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];
    
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
    
        $user->update($data);

        return redirect()->route('user.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully!');
    }
}
