<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">DASHBOARD</li>
            <li>
                <a href="{{ url('/') }}">
                    <i class="fa fa-home"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="{{ request()->is('user*') ? 'active' : '' }}">
                <a href="{{ route('app.user.index') }}">
                    <i class="fa fa-user"></i> <span>Users</span>
                </a>
            </li>

            <li class="{{ request()->is('category*') ? 'active' : '' }}">
                <a href="{{ route('app.category.index') }}">
                    <i class="fa fa-list"></i><span>Categories</span>
                </a>
            </li>

            <li class="{{ request()->is('product*') ? 'active' : '' }}">
                <a href="{{ route('app.product.index') }}">
                    <i class="fa fa-list"></i><span>Products</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
