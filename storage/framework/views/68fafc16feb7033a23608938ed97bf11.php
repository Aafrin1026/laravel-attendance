<?php $__env->startSection('title', 'My Attendance'); ?>
<?php $__env->startSection('header', 'My Attendance'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.attendance-summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;margin-bottom:2rem;}
.summary-card{background:white;border-radius:10px;padding:1.5rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.08);}
.summary-card .number{font-size:2rem;font-weight:bold;}
.summary-card label{font-size:.85rem;color:#666;display:block;margin-top:.3rem;}
.present-card{border-top:4px solid #28a745;} .present-card .number{color:#28a745;}
.absent-card {border-top:4px solid #dc3545;} .absent-card  .number{color:#dc3545;}
.late-card   {border-top:4px solid #ffc107;} .late-card    .number{color:#ffc107;}
.percent-card{border-top:4px solid #667eea;} .percent-card .number{color:#667eea;}
.status-present{background:#28a745;color:white;padding:.2rem .5rem;border-radius:10px;font-size:.8rem;font-weight:bold;}
.status-absent {background:#dc3545;color:white;padding:.2rem .5rem;border-radius:10px;font-size:.8rem;font-weight:bold;}
.status-late   {background:#ffc107;color:#212529;padding:.2rem .5rem;border-radius:10px;font-size:.8rem;font-weight:bold;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <h3>Welcome, <?php echo e($user['name']); ?>!</h3>
    <p>Department: <?php echo e($user['department']); ?> &nbsp;|&nbsp; Email: <?php echo e($user['email']); ?></p>
</div>

<div class="courses-section">
    <h3>Overall Summary</h3>
    <div class="attendance-summary">
        <div class="summary-card present-card"><div class="number"><?php echo e($overall['present']); ?></div><label>Present</label></div>
        <div class="summary-card absent-card"> <div class="number"><?php echo e($overall['absent']); ?></div><label>Absent</label></div>
        <div class="summary-card late-card">   <div class="number"><?php echo e($overall['late']); ?></div><label>Late</label></div>
        <div class="summary-card percent-card"><div class="number"><?php echo e($overall['percentage']); ?>%</div><label>Attendance %</label></div>
    </div>
</div>

<div class="enrollments-section">
    <h3>Attendance by Subject</h3>
    <table>
        <thead><tr><th>Subject</th><th>Department</th><th>Present</th><th>Absent</th><th>Late</th><th>Total</th><th>%</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $bySubject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $pct = $s['total'] > 0 ? round((($s['present']+$s['late'])/$s['total'])*100,1) : 0; ?>
        <tr>
            <td><strong><?php echo e($s['code']); ?></strong> - <?php echo e($s['name']); ?></td>
            <td><?php echo e($s['department']); ?></td>
            <td style="color:#28a745;font-weight:bold"><?php echo e($s['present']); ?></td>
            <td style="color:#dc3545;font-weight:bold"><?php echo e($s['absent']); ?></td>
            <td style="color:#ffc107;font-weight:bold"><?php echo e($s['late']); ?></td>
            <td><?php echo e($s['total']); ?></td>
            <td style="color:<?php echo e($pct>=75?'#28a745':($pct>=50?'#ffc107':'#dc3545')); ?>;font-weight:bold"><?php echo e($pct); ?>%</td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<div class="enrollments-section">
    <h3>All Attendance Records</h3>
    <div class="search-bar"><input type="text" id="searchInput" placeholder="Search records..." oninput="filterRows(this.value)"></div>
    <table id="recordsTable">
        <thead><tr><th>Subject</th><th>Date</th><th>Status</th><th>Department</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($r->subject_code); ?> - <?php echo e($r->subject_name); ?></td>
            <td><?php echo e($r->date); ?></td>
            <td><span class="status-<?php echo e($r->status); ?>"><?php echo e(ucfirst($r->status)); ?></span></td>
            <td><?php echo e($r->department_name); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function filterRows(term) {
    document.querySelectorAll('#recordsTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term.toLowerCase()) ? '' : 'none';
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-attendance\resources\views/student/attendance.blade.php ENDPATH**/ ?>