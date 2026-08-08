<?php $__env->startSection('title', 'Manage Students'); ?>
<?php $__env->startSection('header', 'Manage Students'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.modal{display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.5);}
.modal-content{background:white;margin:5% auto;padding:2rem;border-radius:8px;width:90%;max-width:500px;position:relative;}
.modal-close{position:absolute;right:1rem;top:1rem;font-size:1.5rem;cursor:pointer;background:none;border:none;}
.btn-edit{background:#ffc107;color:#212529;padding:.3rem .7rem;border:none;border-radius:4px;cursor:pointer;font-size:.85rem;}
.btn-delete{background:#dc3545;color:white;padding:.3rem .7rem;border:none;border-radius:4px;cursor:pointer;font-size:.85rem;}
input,select{width:100%;padding:.7rem;border:2px solid #ddd;border-radius:4px;margin-top:.3rem;font-size:1rem;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="crud-section" style="background:white;padding:2rem;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h3>Students (<?php echo e($students->count()); ?>)</h3>
        <button class="btn btn-primary" onclick="openModal('addModal')">+ Add Student</button>
    </div>

    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Department</th><th>Actions</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($s->id); ?></td>
            <td><?php echo e($s->name); ?></td>
            <td><?php echo e($s->email); ?></td>
            <td><?php echo e($s->department_name); ?></td>
            <td style="display:flex;gap:.5rem;">
                <button class="btn-edit" onclick="openEdit(<?php echo e($s->id); ?>,'<?php echo e(addslashes($s->name)); ?>','<?php echo e($s->email); ?>',<?php echo e($s->department_id); ?>)">Edit</button>
                <form method="POST" action="<?php echo e(route('admin.students.destroy', $s->id)); ?>" onsubmit="return confirm('Delete this student?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn-delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<div id="addModal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('addModal')">&times;</button>
        <h3>Add Student</h3>
        <form method="POST" action="<?php echo e(route('admin.students.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group"><label>Name:</label><input type="text" name="name" required></div>
            <div class="form-group" style="margin-top:1rem;"><label>Email:</label><input type="email" name="email" required></div>
            <div class="form-group" style="margin-top:1rem;">
                <label>Department:</label>
                <select name="department_id" required>
                    <option value="">Select Department</option>
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group" style="margin-top:1rem;"><label>Password (default: student123):</label><input type="password" name="password" placeholder="Leave blank for default"></div>
            <div style="margin-top:1.5rem;display:flex;gap:.5rem;">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
        <h3>Edit Student</h3>
        <form method="POST" id="editForm" action="">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="form-group"><label>Name:</label><input type="text" name="name" id="editName" required></div>
            <div class="form-group" style="margin-top:1rem;"><label>Email:</label><input type="email" name="email" id="editEmail" required></div>
            <div class="form-group" style="margin-top:1rem;">
                <label>Department:</label>
                <select name="department_id" id="editDept" required>
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div style="margin-top:1.5rem;display:flex;gap:.5rem;">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openModal(id) { document.getElementById(id).style.display = 'block'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }
function openEdit(id, name, email, deptId) {
    document.getElementById('editName').value  = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editDept').value  = deptId;
    document.getElementById('editForm').action = `/admin/students/${id}`;
    openModal('editModal');
}
window.onclick = e => { if (e.target.classList.contains('modal')) e.target.style.display = 'none'; }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-attendance\resources\views/admin/students.blade.php ENDPATH**/ ?>