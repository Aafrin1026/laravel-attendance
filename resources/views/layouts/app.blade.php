<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Attendance System') - ICST University</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
</head>
<body>
<div class="container">
    <header>
        <h1>ICST University</h1>
        <h2>@yield('header', 'Attendance Management System')</h2>
    </header>

    <nav>
        <ul>
            @if(session('user.role') === 'admin')
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('admin.attendance.view') }}" class="{{ request()->routeIs('admin.attendance.view') ? 'active' : '' }}">View Attendance</a></li>
                <li><a href="{{ route('admin.attendance.mark') }}" class="{{ request()->routeIs('admin.attendance.mark') ? 'active' : '' }}">Mark Attendance</a></li>
                <li><a href="{{ route('admin.students') }}" class="{{ request()->routeIs('admin.students') ? 'active' : '' }}">Students</a></li>
                <li><a href="{{ route('admin.subjects') }}" class="{{ request()->routeIs('admin.subjects') ? 'active' : '' }}">Subjects</a></li>
                <li><a href="{{ route('admin.departments') }}" class="{{ request()->routeIs('admin.departments') ? 'active' : '' }}">Departments</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf <button type="submit" style="background:none;border:none;color:white;cursor:pointer;padding:1rem;font-size:1rem;">Logout</button>
                    </form>
                </li>
            @elseif(session('user.role') === 'student')
                <li><a href="{{ route('student.attendance') }}" class="{{ request()->routeIs('student.attendance') ? 'active' : '' }}">My Attendance</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf <button type="submit" style="background:none;border:none;color:white;cursor:pointer;padding:1rem;font-size:1rem;">Logout</button>
                    </form>
                </li>
            @endif
        </ul>
    </nav>

    <main>
        @if(session('success'))
            <div class="alert alert-success" style="background:#d4edda;color:#155724;padding:1rem;border-radius:6px;margin-bottom:1rem;border:1px solid #c3e6cb;">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error" style="background:#f8d7da;color:#721c24;padding:1rem;border-radius:6px;margin-bottom:1rem;border:1px solid #f5c6cb;">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} ICST University Attendance Management System. All rights reserved.</p>
    </footer>
</div>
@stack('scripts')
</body>
</html>
