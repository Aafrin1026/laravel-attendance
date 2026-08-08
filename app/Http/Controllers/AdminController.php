<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'totalStudents'    => DB::table('students')->where('is_active', 1)->count(),
            'totalSubjects'    => DB::table('subjects')->count(),
            'totalDepartments' => DB::table('departments')->count(),
            'totalAttendance'  => DB::table('attendance')->count(),
            'todayAttendance'  => DB::table('attendance')->whereDate('date', today())->count(),
            'subjects'         => DB::table('subjects')
                                    ->join('departments', 'subjects.department_id', '=', 'departments.id')
                                    ->select('subjects.*', 'departments.name as department_name')
                                    ->orderBy('subjects.name')->get(),
        ];
        return view('admin.dashboard', $data);
    }

    public function students()
    {
        $students    = DB::table('students')
            ->join('departments', 'students.department_id', '=', 'departments.id')
            ->select('students.*', 'departments.name as department_name')
            ->where('students.is_active', 1)
            ->orderBy('students.name')->get();
        $departments = DB::table('departments')->orderBy('name')->get();
        return view('admin.students', compact('students', 'departments'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|unique:students,email',
            'department_id' => 'required|exists:departments,id',
            'password'      => 'nullable|string|min:6',
        ]);

        DB::table('students')->insert([
            'name'          => $request->name,
            'email'         => $request->email,
            'department_id' => $request->department_id,
            'password'      => Hash::make($request->password ?? 'student123'),
            'is_active'     => 1,
            'created_at'    => now(),
        ]);

        return back()->with('success', 'Student created successfully.');
    }

    public function updateStudent(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => "required|email|unique:students,email,{$id}",
            'department_id' => 'required|exists:departments,id',
        ]);

        DB::table('students')->where('id', $id)->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'department_id' => $request->department_id,
        ]);

        return back()->with('success', 'Student updated successfully.');
    }

    public function destroyStudent($id)
    {
        DB::table('students')->where('id', $id)->delete();
        return back()->with('success', 'Student deleted successfully.');
    }

    public function subjects()
    {
        $subjects    = DB::table('subjects')
            ->join('departments', 'subjects.department_id', '=', 'departments.id')
            ->select('subjects.*', 'departments.name as department_name')
            ->orderBy('subjects.name')->get();
        $departments = DB::table('departments')->orderBy('name')->get();
        return view('admin.subjects', compact('subjects', 'departments'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'subject_code'  => 'required|string|max:20|unique:subjects,subject_code',
            'department_id' => 'required|exists:departments,id',
        ]);

        DB::table('subjects')->insert([
            'name'          => $request->name,
            'subject_code'  => strtoupper($request->subject_code),
            'department_id' => $request->department_id,
            'created_at'    => now(),
        ]);

        return back()->with('success', 'Subject created successfully.');
    }

    public function updateSubject(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'subject_code'  => "required|string|max:20|unique:subjects,subject_code,{$id}",
            'department_id' => 'required|exists:departments,id',
        ]);

        DB::table('subjects')->where('id', $id)->update([
            'name'          => $request->name,
            'subject_code'  => strtoupper($request->subject_code),
            'department_id' => $request->department_id,
        ]);

        return back()->with('success', 'Subject updated successfully.');
    }

    public function destroySubject($id)
    {
        DB::table('subjects')->where('id', $id)->delete();
        return back()->with('success', 'Subject deleted successfully.');
    }

    public function departments()
    {
        $departments = DB::table('departments')->orderBy('name')->get();
        return view('admin.departments', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:departments,name']);
        DB::table('departments')->insert(['name' => $request->name, 'created_at' => now()]);
        return back()->with('success', 'Department created successfully.');
    }

    public function updateDepartment(Request $request, $id)
    {
        $request->validate(['name' => "required|string|max:100|unique:departments,name,{$id}"]);
        DB::table('departments')->where('id', $id)->update(['name' => $request->name]);
        return back()->with('success', 'Department updated successfully.');
    }

    public function destroyDepartment($id)
    {
        DB::table('departments')->where('id', $id)->delete();
        return back()->with('success', 'Department deleted successfully.');
    }
}
