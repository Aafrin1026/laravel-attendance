<?php $__env->startSection('title', 'Manage Departments'); ?>
<?php $__env->startSection('header', 'Manage Departments'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.modal{display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.5);}
.modal-content{background:white;margin:10% auto;padding:2rem;border-radius:8px;width:90%;max-width:400px;position:relative;}
.modal-close{position:absolute;right:1rem;top:1rem;font-size:1.5rem;cursor:pointer;background:none;border:none;}
.btn-edit{background:#ffc107;color:#212529;padding:.3rem .7rem;border:none;border-radius:4px;cursor:pointer;font-size:.85rem;}
.btn-delete{background:#dc3545;color:white;padding:.3rem .7rem;border:none;border-radius:4px;cursor:pointer;font-size:.85rem;}
input{width:100%;padding:.7rem;border:2px solid #ddd;border-radius:4px;margin-top:.3rem;font-size:1rem;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="crud-section" style="background:white;padding:2rem;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h3>Departments (<?php echo e($departments->count()); ?>)</h3>
        <button class="btn btn-primary" onclick="openModal('addModal')">+ Add Department</button>
    </div>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($d->id); ?></td><td><?php echo e($d->name); ?></td>
            <td style="display:flex;gap:.5rem;">
                <button class="btn-edit" onclick="openEdit(<?php echo e($d->id); ?>,'<?php echo e(addslashes($d->name)); ?>')">Edit</button>
                <form method="POST" action="<?php echo e(route('admin.departments.destroy', $d->id)); ?>" onsubmit="return confirm('Delete this department and all its data?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?> <button type="submit" class="btn-delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<div id="addModal" class="modal"><div class="modal-content">
    <button class="modal-close" onclick="closeModal('addModal')">&times;</button>
    <h3>Add Department</h3>
    <form method="POST" action="<?php echo e(route('admin.departments.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group"><label>Department Name:</label><input type="text" name="name" required></div>
        <div style="margin-top:1.5rem;display:flex;gap:.5rem;">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Cancel</button>
        </div>
    </form>
</div></div>

<div id="editModal" class="modal"><div class="modal-content">
    <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
    <h3>Edit Department</h3>
    <form method="POST" id="editForm" action="">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="form-group"><label>Department Name:</label><input type="text" name="name" id="editName" required></div>
        <div style="margin-top:1.5rem;display:flex;gap:.5rem;">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
        </div>
    </form>
</div></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openModal(id) { document.getElementById(id).style.display='block'; }
function closeModal(id) { document.getElementById(id).style.display='none'; }
function openEdit(id, name) {
    document.getElementById('editName').value = name;
    document.getElementById('editForm').action = `/admin/departments/${id}`;
    openModal('editModal');
}
window.onclick = e => { if (e.target.classList.contains('modal')) e.target.style.display='none'; }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-attendance\resources\views/admin/departments.blade.php ENDPATH**/ ?>