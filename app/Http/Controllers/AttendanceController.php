<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.attendance.view');
    }

    public function markForm()
    {
        $subjects = DB::table('subjects')
            ->join('departments', 'subjects.department_id', '=', 'departments.id')
            ->select('subjects.*', 'departments.name as department_name')
            ->orderBy('subjects.name')->get();

        $students = DB::table('students')
            ->join('departments', 'students.department_id', '=', 'departments.id')
            ->select('students.id', 'students.name', 'students.email', 'departments.name as department_name')
            ->where('students.is_active', 1)
            ->orderBy('students.name')->get();

        return view('admin.mark_attendance', compact('subjects', 'students'));
    }

    public function bulkSave(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'date'       => 'required|date',
            'records'    => 'required|array',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.status'     => 'required|in:present,absent,late',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->records as $record) {
                DB::table('attendance')->upsert(
                    [
                        'student_id' => $record['student_id'],
                        'subject_id' => $request->subject_id,
                        'date'       => $request->date,
                        'status'     => $record['status'],
                        'marked_at'  => now(),
                    ],
                    ['student_id', 'subject_id', 'date'],
                    ['status', 'marked_at']
                );
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Attendance saved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function view()
    {
        $students = DB::table('students')
            ->join('departments', 'students.department_id', '=', 'departments.id')
            ->select('students.id', 'students.name', 'departments.name as department_name')
            ->where('students.is_active', 1)->orderBy('students.name')->get();

        $subjects = DB::table('subjects')
            ->join('departments', 'subjects.department_id', '=', 'departments.id')
            ->select('subjects.*', 'departments.name as department_name')
            ->orderBy('subjects.name')->get();

        return view('admin.view_attendance', compact('students', 'subjects'));
    }

    public function getData(Request $request)
    {
        $studentId = $request->query('student_id');
        $subjectId = $request->query('subject_id');
        $date      = $request->query('date');

        $query = DB::table('attendance')
            ->join('students',    'attendance.student_id', '=', 'students.id')
            ->join('subjects',    'attendance.subject_id', '=', 'subjects.id')
            ->join('departments as sd', 'students.department_id', '=', 'sd.id')
            ->join('departments as subd', 'subjects.department_id', '=', 'subd.id')
            ->select(
                'attendance.*',
                'students.name as student_name',
                'students.email as student_email',
                'subjects.name as subject_name',
                'subjects.subject_code',
                'sd.name as student_department',
                'subd.name as subject_department'
            );

        if ($studentId) $query->where('attendance.student_id', $studentId);
        if ($subjectId) $query->where('attendance.subject_id', $subjectId);
        if ($date)      $query->whereDate('attendance.date', $date);

        $records = $query->orderBy('attendance.date', 'desc')->get();
        return response()->json(['success' => true, 'data' => $records]);
    }
}
