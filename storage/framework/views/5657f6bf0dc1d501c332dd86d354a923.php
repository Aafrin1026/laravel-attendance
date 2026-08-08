<?php $__env->startSection('title', 'View Attendance'); ?>
<?php $__env->startSection('header', 'View Attendance'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.status-present{background:#28a745;color:white;padding:.2rem .5rem;border-radius:10px;font-size:.8rem;font-weight:bold;}
.status-absent {background:#dc3545;color:white;padding:.2rem .5rem;border-radius:10px;font-size:.8rem;font-weight:bold;}
.status-late   {background:#ffc107;color:#212529;padding:.2rem .5rem;border-radius:10px;font-size:.8rem;font-weight:bold;}
.summary-bar   {display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;}
.summary-item  {background:#f8f9fa;border-radius:8px;padding:.75rem 1.25rem;border-left:4px solid #667eea;}
.summary-item span{font-size:1.4rem;font-weight:bold;color:#667eea;display:block;}
.summary-item label{font-size:.85rem;color:#666;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="filter-section">
    <h3>Filter Attendance</h3>
    <div class="filter-controls">
        <div class="form-group">
            <label>View By:</label>
            <select id="viewType" onchange="handleViewChange(this.value)" style="width:100%;padding:.7rem;border:2px solid #ddd;border-radius:4px;margin-top:.3rem;">
                <option value="all">All Records</option>
                <option value="by_student">By Student</option>
                <option value="by_subject">By Subject</option>
                <option value="by_date">By Subject &amp; Date</option>
            </select>
        </div>
        <div class="form-group" id="studentGroup" style="display:none;">
            <label>Student:</label>
            <select id="studentSelect" style="width:100%;padding:.7rem;border:2px solid #ddd;border-radius:4px;margin-top:.3rem;">
                <option value="">Choose student...</option>
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?> (<?php echo e($s->department_name); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="form-group" id="subjectGroup" style="display:none;">
            <label>Subject:</label>
            <select id="subjectSelect" style="width:100%;padding:.7rem;border:2px solid #ddd;border-radius:4px;margin-top:.3rem;">
                <option value="">Choose subject...</option>
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s->id); ?>"><?php echo e($s->subject_code); ?> - <?php echo e($s->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="form-group" id="dateGroup" style="display:none;">
            <label>Date:</label>
            <input type="date" id="dateSelect" value="<?php echo e(date('Y-m-d')); ?>" style="width:100%;padding:.7rem;border:2px solid #ddd;border-radius:4px;margin-top:.3rem;">
        </div>
        <button class="btn btn-primary" onclick="applyFilter()" style="align-self:flex-end;">Apply Filter</button>
    </div>
</div>

<div class="enrollments-section">
    <h3 id="recordsTitle">All Attendance Records</h3>
    <div class="search-bar"><input type="text" id="searchInput" placeholder="Search records..." oninput="filterRows(this.value)"></div>
    <div id="summaryBar" class="summary-bar" style="display:none;"></div>
    <div id="recordsContainer"><p style="color:#888;">Loading...</p></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const BASE = '<?php echo e(route('admin.attendance.data')); ?>';

function handleViewChange(v) {
    document.getElementById('studentGroup').style.display = v==='by_student' ? 'block' : 'none';
    document.getElementById('subjectGroup').style.display = (v==='by_subject'||v==='by_date') ? 'block' : 'none';
    document.getElementById('dateGroup').style.display    = v==='by_date' ? 'block' : 'none';
}

async function applyFilter() {
    const v = document.getElementById('viewType').value;
    let url = BASE;
    let title = 'All Records';

    if (v === 'by_student') {
        const id = document.getElementById('studentSelect').value;
        if (!id) { alert('Select a student'); return; }
        url += `?student_id=${id}`;
        title = document.getElementById('studentSelect').selectedOptions[0].textContent;
    } else if (v === 'by_subject') {
        const id = document.getElementById('subjectSelect').value;
        if (!id) { alert('Select a subject'); return; }
        url += `?subject_id=${id}`;
        title = document.getElementById('subjectSelect').selectedOptions[0].textContent;
    } else if (v === 'by_date') {
        const sid = document.getElementById('subjectSelect').value;
        const date = document.getElementById('dateSelect').value;
        if (!sid || !date) { alert('Select subject and date'); return; }
        url += `?subject_id=${sid}&date=${date}`;
        title = `${document.getElementById('subjectSelect').selectedOptions[0].textContent} — ${date}`;
    }

    const res  = await fetch(url);
    const data = await res.json();
    if (!data.success) { alert('Error loading records'); return; }

    document.getElementById('recordsTitle').textContent = `${title} (${data.data.length} records)`;
    showSummary(data.data, v);
    renderTable(data.data, v);
}

function showSummary(records, view) {
    if (view === 'all') { document.getElementById('summaryBar').style.display='none'; return; }
    const present = records.filter(r=>r.status==='present').length;
    const absent  = records.filter(r=>r.status==='absent').length;
    const late    = records.filter(r=>r.status==='late').length;
    const bar = document.getElementById('summaryBar');
    bar.style.display = 'flex';
    bar.innerHTML = `
        <div class="summary-item"><span>${present}</span><label>Present</label></div>
        <div class="summary-item" style="border-color:#dc3545"><span style="color:#dc3545">${absent}</span><label>Absent</label></div>
        <div class="summary-item" style="border-color:#ffc107"><span style="color:#ffc107">${late}</span><label>Late</label></div>
        <div class="summary-item" style="border-color:#6c757d"><span style="color:#6c757d">${records.length}</span><label>Total</label></div>
    `;
}

function badge(s) { return `<span class="status-${s}">${s.charAt(0).toUpperCase()+s.slice(1)}</span>`; }

function renderTable(records, view) {
    if (!records.length) { document.getElementById('recordsContainer').innerHTML='<p>No records found.</p>'; return; }
    let cols, rows;
    if (view === 'by_student') {
        cols = '<tr><th>Subject</th><th>Date</th><th>Status</th><th>Department</th></tr>';
        rows = records.map(r=>`<tr><td>${r.subject_code} - ${r.subject_name}</td><td>${r.date}</td><td>${badge(r.status)}</td><td>${r.subject_department}</td></tr>`).join('');
    } else if (view === 'by_subject' || view === 'by_date') {
        cols = '<tr><th>Student</th><th>Email</th><th>Date</th><th>Status</th><th>Department</th></tr>';
        rows = records.map(r=>`<tr><td>${r.student_name}</td><td>${r.student_email}</td><td>${r.date}</td><td>${badge(r.status)}</td><td>${r.student_department}</td></tr>`).join('');
    } else {
        cols = '<tr><th>Student</th><th>Subject</th><th>Date</th><th>Status</th><th>Student Dept.</th></tr>';
        rows = records.map(r=>`<tr><td>${r.student_name}</td><td>${r.subject_code} - ${r.subject_name}</td><td>${r.date}</td><td>${badge(r.status)}</td><td>${r.student_department}</td></tr>`).join('');
    }
    document.getElementById('recordsContainer').innerHTML = `<table><thead>${cols}</thead><tbody>${rows}</tbody></table>`;
}

function filterRows(term) {
    document.querySelectorAll('#recordsContainer table tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term.toLowerCase()) ? '' : 'none';
    });
}

applyFilter();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-attendance\resources\views/admin/view_attendance.blade.php ENDPATH**/ ?>