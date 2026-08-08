<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('header', 'Admin Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.stats-grid{display:flex;gap:1rem;margin:2rem 0;justify-content:center;flex-wrap:wrap;}
.stat-card{display:flex;flex-direction:column;align-items:center;background:#fff;border-radius:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.07);padding:1.2rem 2rem;min-width:180px;}
.stat-icon{font-size:2.5rem;color:#2563eb;margin-bottom:.5rem;}
.stat-card h4{margin:0;font-size:1.1rem;color:#222e3a;}
.stat-card span{margin-top:.3rem;font-size:1.5rem;font-weight:bold;color:#2563eb;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <h3>Welcome, <?php echo e(session('user.name')); ?>!</h3>
    <p>Manage student attendance across all departments and subjects from this dashboard.</p>
</div>

<div class="stats-section">
    <h3>System Statistics</h3>
    <div class="stats-grid">
        <div class="stat-card"><i class="fa-solid fa-users stat-icon"></i><h4>Total Students</h4><span><?php echo e($totalStudents); ?></span></div>
        <div class="stat-card"><i class="fa-solid fa-book stat-icon"></i><h4>Total Subjects</h4><span><?php echo e($totalSubjects); ?></span></div>
        <div class="stat-card"><i class="fa-solid fa-calendar-check stat-icon"></i><h4>Today's Records</h4><span><?php echo e($todayAttendance); ?></span></div>
        <div class="stat-card"><i class="fa-solid fa-building-columns stat-icon"></i><h4>Departments</h4><span><?php echo e($totalDepartments); ?></span></div>
    </div>
</div>

<div class="courses-section">
    <h3>Subjects Overview</h3>
    <div class="search-bar">
        <input type="text" id="subjectSearch" placeholder="Search subjects..." oninput="filterCards(this.value)">
    </div>
    <div class="courses-grid" id="subjectsGrid">
        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="course-card subject-card">
            <h4><?php echo e($subject->name); ?></h4>
            <p><strong>Code:</strong> <?php echo e($subject->subject_code); ?></p>
            <p><strong>Department:</strong> <?php echo e($subject->department_name); ?></p>
            <p style="margin-top:1rem;">
                <a href="<?php echo e(route('admin.attendance.mark')); ?>" class="btn btn-primary" style="font-size:.85rem;padding:.4rem .9rem;">Mark Attendance</a>
            </p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function filterCards(term) {
    document.querySelectorAll('.subject-card').forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(term.toLowerCase()) ? 'block' : 'none';
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-attendance\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>