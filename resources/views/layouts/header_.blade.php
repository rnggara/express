@actionStart('accounts', 'access')
<!--begin::Menu item-->
<div class="menu-item px-3">
    <a href="{{ route('company.user', base64_encode(Session::get('company_id'))) }}"
        class="menu-link px-3 d-flex">
        <span class="menu-title">Accounts</span>

    </a>
</div>
<!--end::Menu item-->
@actionEnd
@actionStart('employee', 'access')
<!--begin::Menu item-->
<div class="menu-item px-3">
    <a href="{{ route('employee.index') }}" class="menu-link px-3 d-flex">
        <span class="menu-title">Employee</span>

    </a>
</div>
<!--end::Menu item-->
@actionEnd
@actionStart('employee', 'access')
<!--begin::Menu item-->
<div class="menu-item px-3">
    <a href="{{ route('emp.mt.index') }}" class="menu-link px-3 d-flex">
        <span class="menu-title">Employee Attendance</span>

    </a>
</div>
<!--end::Menu item-->
@actionEnd
@actionStart('storage', 'access')
<!--begin::Menu item-->
<div class="menu-item px-3">
    <a href="{{ route('wh.index') }}" class="menu-link px-3 d-flex">
        <span class="menu-title">Storages</span>

    </a>
</div>
<!--end::Menu item-->
@actionEnd
@actionStart('client', 'access')
<!--begin::Menu item-->
<div class="menu-item px-3">
    <a href="{{ route('marketing.client.index') }}" class="menu-link px-3 d-flex">
        <span class="menu-title">Clients</span>

    </a>
</div>
<!--end::Menu item-->
@actionEnd
@actionStart('variables', 'access')
<!--begin::Menu item-->
<div class="menu-item px-3">
    <a href="{{ route('employee_variables', Session::get('company_id')) }}"
        class="menu-link px-3 d-flex">
        <span class="menu-title">Variables</span>

    </a>
</div>
<!--end::Menu item-->
@actionEnd
@actionStart('activity_test', 'access')
<!--begin::Menu item-->
<div class="menu-item px-3">
    <a href="{{ route('hrd.test.index') }}" class="menu-link px-3 d-flex">
        <span class="menu-title">Activity Test</span>

    </a>
</div>
<!--end::Menu item-->
@actionEnd
@actionStart('job_vacancy', 'access')
<!--begin::Menu item-->
<div class="menu-item px-3">
    <a href="{{ route('job.index') }}" class="menu-link px-3 d-flex">
        <span class="menu-title">Job Vacancy</span>

    </a>
</div>
<!--end::Menu item-->
@actionEnd
