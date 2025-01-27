<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
     <a href="#" class="brand-link">
      <img src="{{ asset('assets') }}/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->person->name ?? "" }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          @if(Auth::user()->role == 'superadmin')
            <x-nav-item :icon="'fas fa-list'" :text="'Daftar Kategori'" :href="route('category.list')" />
            <x-nav-item :icon="'fas fa-list'" :text="'Daftar Akun'" :href="route('account.index')" />
          @endif

          @if(Auth::user()->role == 'admin' || Auth::user()->role == 'director')
            <x-nav-item :icon="'fas fa-fw fa-tachometer-alt'" :text="'Dashboard'" :href="'/dashboard'" />
            <li class="nav-header">Aset Tetap</li>
              <x-nav-item :icon="'fas fa-folder'" :text="'Stok'" :href="route('stock.index', ['type' => 'asset'])" />
              <x-nav-item :icon="'fas fa-list'" :text="'Barang Masuk'" :href="route('stock.index-in', ['type' => 'asset'])" />
              <x-nav-item :icon="'fas fa-list'" :text="'Barang Keluar'" :href="route('stock.index-out', ['type' => 'asset'])" />

            <li class="nav-header">Aset Tidak Tetap</li>
              <x-nav-item :icon="'fas fa-folder'" :text="'Stok'" :href="route('stock.index', ['type' => 'non-asset'])" />
              <x-nav-item :icon="'fas fa-list'" :text="'Barang Masuk'" :href="route('record-non-asset-in.index', ['type' => 'non-asset'])" />
              <x-nav-item :icon="'fas fa-list'" :text="'Barang Keluar'" :href="route('record-non-asset-out.index', ['type' => 'non-asset'])" />
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
