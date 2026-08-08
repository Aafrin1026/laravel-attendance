@extends('layouts.app')
@section('title', 'Manage Subjects')
@section('header', 'Manage Subjects')

@push('styles')
<style>
.modal{display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.5);}
.modal-content{background:white;margin:5% auto;padding:2rem;border-radius:8px;width:90%;max-width:500px;position:relative;}
.modal-close{position:absolute;right:1rem;top:1rem;font-size:1.5rem;cursor:pointer;background:none;border:none;}
.btn-edit{background:#ffc107;color:#212529;padding:.3rem .7rem;border:none;border-radius:4px;cursor:pointer;font-size:.85rem;}
.btn-delete{background:#dc3545;color:white;padding:.3rem .7rem;border:none;border-radius:4px;cursor:pointer;font-size:.85rem;}
input,select{width:100%;padding:.7rem;border:2px solid #ddd;border-radius:4px;margin-top:.3rem;font-size:1rem;}
</style>
@endpush

@section('content')
<div class="crud-section" style="background:white;padding:2rem;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h3>Subjects ({{ $subjects->count() }})</h3>
        <button class="btn btn-primary" onclick="openModal('addModal')">+ Add Subject</button>
    </div>
    <table>
        <thead><tr><th>ID</th><th>Code</th><th>Name</th><th>Department</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($subjects as $s)
        <tr>
            <td>{{ $s->id }}</td><td>{{ $s->subject_code }}</td><td>{{ $s->name }}</td><td>{{ $s->department_name }}</td>
            <td style="display:flex;gap:.5rem;">
                <button class="btn-edit" onclick="openEdit({{ $s->id }},'{{ addslashes($s->name) }}','{{ $s->subject_code }}',{{ $s->department_id }})">Edit</button>
                <form method="POST" action="{{ route('admin.subjects.destroy', $s->id) }}" onsubmit="return confirm('Delete this subject?')">
                    @csrf @method('DELETE') <button type="submit" class="btn-delete">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div id="addModal" class="modal"><div class="modal-content">
    <button class="modal-close" onclick="closeModal('addModal')">&times;</button>
    <h3>Add Subject</h3>
    <form method="POST" action="{{ route('admin.subjects.store') }}">
        @csrf
        <div class="form-group"><label>Subject Name:</label><input type="text" name="name" required></div>
        <div class="form-group" style="margin-top:1rem;"><label>Subject Code:</label><input type="text" name="subject_code" required placeholder="e.g. CS101"></div>
        <div class="form-group" style="margin-top:1rem;"><label>Department:</label>
            <select name="department_id" required><option value="">Select Department</option>
                @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
            </select>
        </div>
        <div style="margin-top:1.5rem;display:flex;gap:.5rem;">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Cancel</button>
        </div>
    </form>
</div></div>

<div id="editModal" class="modal"><div class="modal-content">
    <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
    <h3>Edit Subject</h3>
    <form method="POST" id="editForm" action="">
        @csrf @method('PUT')
        <div class="form-group"><label>Subject Name:</label><input type="text" name="name" id="editName" required></div>
        <div class="form-group" style="margin-top:1rem;"><label>Subject Code:</label><input type="text" name="subject_code" id="editCode" required></div>
        <div class="form-group" style="margin-top:1rem;"><label>Department:</label>
            <select name="department_id" id="editDept" required><option value="">Select</option>
                @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
            </select>
        </div>
        <div style="margin-top:1.5rem;display:flex;gap:.5rem;">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
        </div>
    </form>
</div></div>
@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).style.display='block'; }
function closeModal(id) { document.getElementById(id).style.display='none'; }
function openEdit(id, name, code, deptId) {
    document.getElementById('editName').value = name;
    document.getElementById('editCode').value = code;
    document.getElementById('editDept').value = deptId;
    document.getElementById('editForm').action = `/admin/subjects/${id}`;
    openModal('editModal');
}
window.onclick = e => { if (e.target.classList.contains('modal')) e.target.style.display='none'; }
</script>
@endpush
