<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    {{-- Dashboard --}}
    <li class="nav-item">
      <a class="nav-link" href="{{ url('dashboard') }}">
        <i class="mdi mdi-view-dashboard menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    {{-- Masters --}}
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#mastersMenu" aria-expanded="false">
        <i class="mdi mdi-database menu-icon"></i>
        <span class="menu-title">Masters</span>
        <i class="menu-arrow"></i>
      </a>

      <div class="collapse" id="mastersMenu">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('course') }}">Course</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('scheme') }}">Scheme</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('students') }}">Students</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('fees') }}">Fees</a>
          </li>
                <li class="nav-item">
            <a class="nav-link" href="{{ url('users') }}">Users</a>
          </li>
        </ul>
      </div>
    </li>

    {{-- Reports --}}
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#reportsMenu" aria-expanded="false">
        <i class="mdi mdi-chart-bar menu-icon"></i>
        <span class="menu-title">Reports</span>
        <i class="menu-arrow"></i>
      </a>

      <div class="collapse" id="reportsMenu">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('fees-report') }}">Fees-Date Wise</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('fees-report/student-wise') }}">Fees-Student Wise</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('student-report/date-wise') }}">Student-Date Wise</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('student-report/status-wise') }}">Student-Status Wise</a>
          </li>
        </ul>
      </div>
    </li>

  </ul>
</nav>
