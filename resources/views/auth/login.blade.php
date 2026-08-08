<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ICST University Attendance</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .login-container{max-width:420px;margin:80px auto;padding:2rem;background:white;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,0.1);}
        .login-header{text-align:center;margin-bottom:2rem;}
        .login-header h1{color:#667eea;margin-bottom:.5rem;}
        .role-selector{display:flex;margin-bottom:2rem;border-radius:8px;overflow:hidden;border:2px solid #e9ecef;}
        .role-btn{flex:1;padding:1rem;text-align:center;background:#f8f9fa;cursor:pointer;border:none;font-size:1rem;transition:all .3s;}
        .role-btn.active{background:#667eea;color:white;}
        .login-btn{width:100%;padding:1rem;background:#667eea;color:white;border:none;border-radius:6px;font-size:1rem;cursor:pointer;transition:background .3s;}
        .login-btn:hover{background:#5a6fd8;}
        .error-box{background:#f8d7da;color:#721c24;padding:.75rem;border-radius:4px;margin-bottom:1rem;border:1px solid #f5c6cb;}
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-header">
        <h1>ICST University</h1>
        <p>Student Attendance Management System</p>
    </div>

    <div class="role-selector">
        <button type="button" class="role-btn active" onclick="setRole('admin', this)">Admin</button>
        <button type="button" class="role-btn" onclick="setRole('student', this)">Student</button>
    </div>

    @if(session('error'))
        <div class="error-box"><i class="fa fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="error-box">
            <ul style="margin:0;padding-left:1.2rem;">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <input type="hidden" name="role" id="roleInput" value="admin">

        <div class="form-group">
            <label id="usernameLabel" for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control"
                   value="{{ old('username') }}" required placeholder="Enter admin username"
                   style="width:100%;padding:.75rem;border:2px solid #ddd;border-radius:4px;font-size:1rem;">
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label for="password">Password:</label>
            <input type="password" name="password" required placeholder="Enter password"
                   style="width:100%;padding:.75rem;border:2px solid #ddd;border-radius:4px;font-size:1rem;">
        </div>

        <button type="submit" class="login-btn" style="margin-top:1.5rem;">Login</button>
    </form>

    <div style="margin-top:1.5rem;padding:1rem;background:#f8f9fa;border-radius:6px;font-size:.88rem;color:#555;">
        <strong>Admin:</strong> admin / Admin@12345<br>
        <strong>Students:</strong> [email] / student123
    </div>
</div>

<script>
function setRole(role, btn) {
    document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('roleInput').value = role;
    const label = document.getElementById('usernameLabel');
    const input = document.getElementById('username');
    if (role === 'admin') {
        label.textContent = 'Username:';
        input.type = 'text';
        input.placeholder = 'Enter admin username';
    } else {
        label.textContent = 'Email:';
        input.type = 'email';
        input.placeholder = 'Enter student email';
    }
}
</script>
</body>
</html>
