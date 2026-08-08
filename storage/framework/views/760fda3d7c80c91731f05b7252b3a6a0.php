<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Attendance System'); ?> - ICST University</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<div class="container">
    <header>
        <h1>ICST University</h1>
        <h2><?php echo $__env->yieldContent('header', 'Attendance Management System'); ?></h2>
    </header>

    <nav>
        <ul>
            <?php if(session('user.role') === 'admin'): ?>
                <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">Home</a></li>
                <li><a href="<?php echo e(route('admin.attendance.view')); ?>" class="<?php echo e(request()->routeIs('admin.attendance.view') ? 'active' : ''); ?>">View Attendance</a></li>
                <li><a href="<?php echo e(route('admin.attendance.mark')); ?>" class="<?php echo e(request()->routeIs('admin.attendance.mark') ? 'active' : ''); ?>">Mark Attendance</a></li>
                <li><a href="<?php echo e(route('admin.students')); ?>" class="<?php echo e(request()->routeIs('admin.students') ? 'active' : ''); ?>">Students</a></li>
                <li><a href="<?php echo e(route('admin.subjects')); ?>" class="<?php echo e(request()->routeIs('admin.subjects') ? 'active' : ''); ?>">Subjects</a></li>
                <li><a href="<?php echo e(route('admin.departments')); ?>" class="<?php echo e(request()->routeIs('admin.departments') ? 'active' : ''); ?>">Departments</a></li>
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline">
                        <?php echo csrf_field(); ?> <button type="submit" style="background:none;border:none;color:white;cursor:pointer;padding:1rem;font-size:1rem;">Logout</button>
                    </form>
                </li>
            <?php elseif(session('user.role') === 'student'): ?>
                <li><a href="<?php echo e(route('student.attendance')); ?>" class="<?php echo e(request()->routeIs('student.attendance') ? 'active' : ''); ?>">My Attendance</a></li>
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline">
                        <?php echo csrf_field(); ?> <button type="submit" style="background:none;border:none;color:white;cursor:pointer;padding:1rem;font-size:1rem;">Logout</button>
                    </form>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <main>
        <?php if(session('success')): ?>
            <div class="alert alert-success" style="background:#d4edda;color:#155724;padding:1rem;border-radius:6px;margin-bottom:1rem;border:1px solid #c3e6cb;">
                <i class="fa fa-check-circle"></i> <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-error" style="background:#f8d7da;color:#721c24;padding:1rem;border-radius:6px;margin-bottom:1rem;border:1px solid #f5c6cb;">
                <i class="fa fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer>
        <p>&copy; <?php echo e(date('Y')); ?> ICST University Attendance Management System. All rights reserved.</p>
    </footer>
</div>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel-attendance\resources\views/layouts/app.blade.php ENDPATH**/ ?>