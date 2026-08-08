<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role'     => 'required|in:admin,student',
        ]);

        $role     = $request->role;
        $username = $request->username;
        $password = $request->password;

        if ($role === 'admin') {
            $user = DB::table('admin_users')->where('username', $username)->first();
            if ($user && Hash::check($password, $user->password)) {
                session(['user' => [
                    'id'        => $user->id,
                    'name'      => $user->full_name,
                    'username'  => $user->username,
                    'email'     => $user->email,
                    'role'      => 'admin',
                ]]);
                return redirect()->route('admin.dashboard');
            }
        } else {
            $user = DB::table('students')
                ->join('departments', 'students.department_id', '=', 'departments.id')
                ->where('students.email', $username)
                ->where('students.is_active', 1)
                ->select('students.*', 'departments.name as department_name')
                ->first();

            if ($user && Hash::check($password, $user->password)) {
                session(['user' => [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'department' => $user->department_name,
                    'role'       => 'student',
                ]]);
                return redirect()->route('student.attendance');
            }
        }

        return back()->with('error', 'Invalid credentials. Please try again.');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
