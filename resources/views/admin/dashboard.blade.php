@extends('layouts.app')
@section('title', 'Dashboard')
@section('header', 'Admin Dashboard')

@push('styles')
<style>
.stats-grid{display:flex;gap:1rem;margin:2rem 0;justify-content:center;flex-wrap:wrap;}
.stat-card{display:flex;flex-direction:column;align-items:center;background:#fff;border-radius:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.07);padding:1.2rem 2rem;min-width:180px;}
.stat-icon{font-size:2.5rem;color:#2563eb;margin-bottom:.5rem;}
.stat-card h4{margin:0;font-size:1.1rem;color:#222e3a;}
.stat-card span{margin-top:.3rem;font-size:1.5rem;font-weight:bold;color:#2563eb;}
</style>
@endpush

@section('content')
<div class="welcome-section">
    <h3>Welcome, {{ session('user.name') }}!</h3>
    <p>Manage student attendance across all departments and subjects from this dashboard.</p>
</div>

<div class="stats-section">
    <h3>System Statistics</h3>
    <div class="stats-grid">
        <div class="stat-card"><i class="fa-solid fa-users stat-icon"></i><h4>Total Students</h4><span>{{ $totalStudents }}</span></div>
        <div class="stat-card"><i class="fa-solid fa-book stat-icon"></i><h4>Total Subjects</h4><span>{{ $totalSubjects }}</span></div>
        <div class="stat-card"><i class="fa-solid fa-calendar-check stat-icon"></i><h4>Today's Records</h4><span>{{ $todayAttendance }}</span></div>
        <div class="stat-card"><i class="fa-solid fa-building-columns stat-icon"></i><h4>Departments</h4><span>{{ $totalDepartments }}</span></div>
    </div>
</div>

<div class="courses-section">
    <h3>Subjects Overview</h3>
    <div class="search-bar">
        <input type="text" id="subjectSearch" placeholder="Search subjects..." oninput="filterCards(this.value)">
    </div>
    <div class="courses-grid" id="subjectsGrid">
        @foreach($subjects as $subject)
        <div class="course-card subject-card">
            <h4>{{ $subject->name }}</h4>
            <p><strong>Code:</strong> {{ $subject->subject_code }}</p>
            <p><strong>Department:</strong> {{ $subject->department_name }}</p>
            <p style="margin-top:1rem;">
                <a href="{{ route('admin.attendance.mark') }}" class="btn btn-primary" style="font-size:.85rem;padding:.4rem .9rem;">Mark Attendance</a>
            </p>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterCards(term) {
    document.querySelectorAll('.subject-card').forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(term.toLowerCase()) ? 'block' : 'none';
    });
}
</script>
@endpush
