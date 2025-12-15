<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="/modules/admin/dashboard.php" class="brand-link">
            <!--begin::Brand Image-->
            <img src="/public/img/logo (2).png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light"></span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">
                <!-- START: Customized simplified menu -->

                
            <?php if (hasPermission('dashboard_view')): ?>
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link">
                        <i class="nav-icon bi bi-house-fill"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (hasPermission('donation_view')): ?>    
              <li class="nav-item">
                    <a href="/admin/donasi" class="nav-link" id="menu-donasi">
                        <i class="nav-icon bi bi-heart-fill"></i>
                        <p>Donasi</p>
                    </a>
                </li>
            <?php endif; ?>

               <?php if (hasPermission('list_category')): ?>
                <li class="nav-item">
                    <a href="/admin/kategori" class="nav-link">
                        <i class="nav-icon bi bi-tags-fill"></i>
                        <p>Kategori</p>
                    </a>
                </li>
                <?php endif; ?>


                <?php if (hasPermission('bank_view')): ?>
                <li class="nav-item">
                    <a href="/admin/masterbank" class="nav-link">
                        <i class="nav-icon bi bi-bank2"></i>
                        <p>Master Bank</p>
                    </a>
                </li>
                <?php endif; ?>

               <?php if (hasPermission('post_view')): ?>
                <li class="nav-item">
                    <a href="/admin/post" class="nav-link">
                        <i class="nav-icon bi bi-file-earmark-text-fill"></i>
                        <p>Post</p>
                    </a>
                </li>
                <?php endif; ?>


                <li class="nav-item">
                    <a href="/admin/user" class="nav-link" id="menu-users">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>User</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/admin/role-permission" id="menu-role_permission">
                        <i class="nav-icon bi bi-cassette-fill"></i>
                        Role Permission
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/logout" class="nav-link" id="menu-users">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Logout</p>
                    </a>
                </li>
                <!-- END: Customized simplified menu -->
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->