<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $departments = ['Computer Science','Information Technology','Software Engineering','Data Science','Cybersecurity'];
        foreach ($departments as $dept) {
            DB::table('departments')->insertOrIgnore(['name' => $dept, 'created_at' => now()]);
        }

        $deptIds = DB::table('departments')->pluck('id', 'name');

        $students = [
            ['name'=>'aafrin',  'email'=>'aafrin@icst.edu',  'department_id'=>$deptIds['Computer Science']],
            ['name'=>'Hazeem', 'email'=>'hazeem@icst.edu', 'department_id'=>$deptIds['Computer Science']],
            ['name'=>'Nifra',  'email'=>'nifra@icst.edu',  'department_id'=>$deptIds['Information Technology']],
            ['name'=>'Hilma',  'email'=>'hilma@icst.edu',  'department_id'=>$deptIds['Information Technology']],
            ['name'=>'Anshaf', 'email'=>'anshaf@icst.edu', 'department_id'=>$deptIds['Software Engineering']],
            ['name'=>'Shihab', 'email'=>'shihab@icst.edu', 'department_id'=>$deptIds['Software Engineering']],
            ['name'=>'Afsan',  'email'=>'afsan@icst.edu',  'department_id'=>$deptIds['Data Science']],
        ];
        foreach ($students as $s) {
            DB::table('students')->insertOrIgnore(array_merge($s, [
                'password'   => Hash::make('student123'),
                'is_active'  => 1,
                'created_at' => now(),
            ]));
        }

        $subjects = [
            ['name'=>'Introduction to Programming',   'subject_code'=>'CS101', 'dept'=>'Computer Science'],
            ['name'=>'Data Structures and Algorithms','subject_code'=>'CS201', 'dept'=>'Computer Science'],
            ['name'=>'Database Systems',              'subject_code'=>'CS301', 'dept'=>'Computer Science'],
            ['name'=>'Web Development',               'subject_code'=>'IT101', 'dept'=>'Information Technology'],
            ['name'=>'Network Administration',        'subject_code'=>'IT201', 'dept'=>'Information Technology'],
            ['name'=>'Mobile App Development',        'subject_code'=>'IT301', 'dept'=>'Information Technology'],
            ['name'=>'Software Engineering Principles','subject_code'=>'SE101','dept'=>'Software Engineering'],
            ['name'=>'Agile Development',             'subject_code'=>'SE201', 'dept'=>'Software Engineering'],
            ['name'=>'Software Testing',              'subject_code'=>'SE301', 'dept'=>'Software Engineering'],
            ['name'=>'Machine Learning',              'subject_code'=>'DS101', 'dept'=>'Data Science'],
            ['name'=>'Big Data Analytics',            'subject_code'=>'DS201', 'dept'=>'Data Science'],
            ['name'=>'Data Visualization',            'subject_code'=>'DS301', 'dept'=>'Data Science'],
            ['name'=>'Ethical Hacking',               'subject_code'=>'CY101', 'dept'=>'Cybersecurity'],
            ['name'=>'Network Security',              'subject_code'=>'CY201', 'dept'=>'Cybersecurity'],
            ['name'=>'Digital Forensics',             'subject_code'=>'CY301', 'dept'=>'Cybersecurity'],
        ];
        foreach ($subjects as $s) {
            DB::table('subjects')->insertOrIgnore([
                'name'          => $s['name'],
                'subject_code'  => $s['subject_code'],
                'department_id' => $deptIds[$s['dept']],
                'created_at'    => now(),
            ]);
        }

        DB::table('admin_users')->insertOrIgnore([
            'username'   => 'admin',
            'password'   => Hash::make('Admin@12345'),
            'full_name'  => 'System Administrator',
            'email'      => 'admin@icst.edu',
            'created_at' => now(),
        ]);

        $studentIds = DB::table('students')->pluck('id', 'email');
        $subjectIds = DB::table('subjects')->pluck('id', 'subject_code');
        $statuses   = ['present','present','present','absent','late'];

        $sampleData = [
            [$studentIds['aafrin@icst.edu'],  $subjectIds['CS101'], 4],
            [$studentIds['hazeem@icst.edu'], $subjectIds['CS101'], 4],
            [$studentIds['nifra@icst.edu'],  $subjectIds['IT101'], 3],
            [$studentIds['hilma@icst.edu'],  $subjectIds['IT101'], 3],
            [$studentIds['anshaf@icst.edu'], $subjectIds['SE101'], 4],
        ];

        foreach ($sampleData as [$sid, $subid, $days]) {
            for ($i = $days; $i >= 1; $i--) {
                DB::table('attendance')->insertOrIgnore([
                    'student_id' => $sid,
                    'subject_id' => $subid,
                    'date'       => now()->subDays($i)->toDateString(),
                    'status'     => $statuses[array_rand($statuses)],
                    'marked_at'  => now(),
                ]);
            }
        }

        $this->command->info('Database seeded successfully!');
    }
}
