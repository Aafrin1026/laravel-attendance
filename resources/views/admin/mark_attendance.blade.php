@extends('layouts.app')
@section('title', 'Mark Attendance')
@section('header', 'Mark Attendance')

@push('styles')
<style>
.status-select{padding:.3rem .5rem;border-radius:4px;border:1px solid #ddd;font-size:.9rem;}
.bulk-actions{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1rem;}
</style>
@endpush

@section('content')
<div class="form-section">
    <h3>Select Subject &amp; Date</h3>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
        <div class="form-group">
            <label>Subject:</label>
            <select id="subjectSelect" style="width:100%;padding:.75rem;border:2px solid #ddd;border-radius:4px;margin-top:.3rem;">
                <option value="">Choose a subject...</option>
                @foreach($subjects as $s)
                <option value="{{ $s->id }}">{{ $s->subject_code }} - {{ $s->name }} ({{ $s->department_name }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Date:</label>
            <input type="date" id="dateSelect" value="{{ date('Y-m-d') }}" style="width:100%;padding:.75rem;border:2px solid #ddd;border-radius:4px;margin-top:.3rem;">
        </div>
    </div>
    <button class="btn btn-primary" onclick="loadStudents()">Load Students</button>
</div>

<div class="form-section" id="attendanceSection" style="display:none;">
    <h3 id="attendanceTitle">Mark Attendance</h3>
    <div class="bulk-actions">
        <button class="btn btn-secondary" onclick="markAll('present')">Mark All Present</button>
        <button class="btn btn-secondary" onclick="markAll('absent')">Mark All Absent</button>
        <button class="btn btn-secondary" onclick="markAll('late')">Mark All Late</button>
    </div>
    <div id="attendanceTableContainer"></div>
    <div style="margin-top:1.5rem;">
        <button class="btn btn-primary" onclick="saveAttendance()">Save Attendance</button>
    </div>
</div>

<div id="msgBox" style="display:none;padding:1rem;border-radius:6px;margin-top:1rem;"></div>
@endsection

@push('scripts')
<script>
const allStudents = @json($students);

async function loadStudents() {
    const subjectId = document.getElementById('subjectSelect').value;
    const date      = document.getElementById('dateSelect').value;
    if (!subjectId || !date) { showMsg('Please select subject and date.', 'error'); return; }

    const existRes = await fetch(`{{ route('admin.attendance.data') }}?subject_id=${subjectId}&date=${date}`);
    const existing = await existRes.json();
    const existMap = {};
    if (existing.success) existing.data.forEach(r => existMap[r.student_id] = r.status);

    renderTable(allStudents, existMap);
    const opt = document.getElementById('subjectSelect').selectedOptions[0].textContent;
    document.getElementById('attendanceTitle').textContent = `Attendance: ${opt} — ${date}`;
    document.getElementById('attendanceSection').style.display = 'block';
}

function renderTable(students, existMap) {
    let html = `<table><thead><tr><th>#</th><th>Student</th><th>Email</th><th>Department</th><th>Status</th></tr></thead><tbody>`;
    students.forEach((s, i) => {
        const status = existMap[s.id] || 'present';
        html += `<tr>
            <td>${i+1}</td><td>${s.name}</td><td>${s.email}</td><td>${s.department_name}</td>
            <td><select id="status_${s.id}" class="status-select">
                <option value="present" ${status==='present'?'selected':''}>Present</option>
                <option value="absent"  ${status==='absent' ?'selected':''}>Absent</option>
                <option value="late"    ${status==='late'   ?'selected':''}>Late</option>
            </select></td>
        </tr>`;
    });
    html += '</tbody></table>';
    document.getElementById('attendanceTableContainer').innerHTML = html;
}

function markAll(status) {
    document.querySelectorAll('.status-select').forEach(s => s.value = status);
}

async function saveAttendance() {
    const subjectId = document.getElementById('subjectSelect').value;
    const date      = document.getElementById('dateSelect').value;
    if (!subjectId || !date || !allStudents.length) { showMsg('Load students first.', 'error'); return; }

    const records = allStudents.map(s => ({
        student_id: s.id,
        status: document.getElementById(`status_${s.id}`)?.value || 'absent'
    }));

    const res  = await fetch('{{ route('admin.attendance.bulk') }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({ subject_id: parseInt(subjectId), date, records })
    });
    const data = await res.json();
    showMsg(data.message, data.success ? 'success' : 'error');
}

function showMsg(msg, type) {
    const box = document.getElementById('msgBox');
    box.textContent = msg;
    box.style.display = 'block';
    box.style.background = type === 'success' ? '#d4edda' : '#f8d7da';
    box.style.color      = type === 'success' ? '#155724' : '#721c24';
    box.style.border     = `1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'}`;
    setTimeout(() => box.style.display = 'none', 4000);
}
</script>
@endpush
