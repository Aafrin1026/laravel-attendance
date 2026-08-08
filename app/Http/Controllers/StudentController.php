<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function myAttendance()
    {
        $user      = session('user');
        $studentId = $user['id'];

        $records = DB::table('attendance')
            ->join('subjects',    'attendance.subject_id', '=', 'subjects.id')
            ->join('departments', 'subjects.department_id', '=', 'departments.id')
            ->select(
                'attendance.*',
                'subjects.name as subject_name',
                'subjects.subject_code',
                'departments.name as department_name'
            )
            ->where('attendance.student_id', $studentId)
            ->orderBy('attendance.date', 'desc')
            ->get();

        $bySubject = [];
        foreach ($records as $r) {
            $key = $r->subject_id;
            if (!isset($bySubject[$key])) {
                $bySubject[$key] = [
                    'name'       => $r->subject_name,
                    'code'       => $r->subject_code,
                    'department' => $r->department_name,
                    'present'    => 0,
                    'absent'     => 0,
                    'late'       => 0,
                    'total'      => 0,
                ];
            }
            $bySubject[$key][$r->status]++;
            $bySubject[$key]['total']++;
        }

        $overall = [
            'present' => $records->where('status', 'present')->count(),
            'absent'  => $records->where('status', 'absent')->count(),
            'late'    => $records->where('status', 'late')->count(),
            'total'   => $records->count(),
        ];
        $overall['percentage'] = $overall['total'] > 0
            ? round((($overall['present'] + $overall['late']) / $overall['total']) * 100, 1)
            : 0;

        return view('student.attendance', compact('records', 'bySubject', 'overall', 'user'));
    }
}
