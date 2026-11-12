<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="./assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Rede Patas"</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Search-->
        <div class="sidebar-search" role="search">
          <label for="sidebar-search-input" class="visually-hidden">Filter menu</label>
          <input
            type="search"
            id="sidebar-search-input"
            class="form-control form-control-sm"
            placeholder="Filter menu…"
            autocomplete="off"
            data-lte-toggle="sidebar-search"
            data-lte-target="#navigation"
          />
          <p class="fs-7 text-secondary mt-2 mb-0" data-lte-search-empty role="status" hidden>
            No matching pages.
          </p>
        </div>
        <!--end::Sidebar Search-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2" aria-label="Main navigation">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              data-accordion="true"
              id="navigation"
            >
            
              <li class="nav-item">
                <a href="{{ route('register') }}" class="nav-link">
                  <i class="nav-icon bi bi-file-earmark"></i>
                  <p>Novo Usuario</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-file-earmark"></i>
                  <p>Listar Usuario</p>
                </a>
              </li>
              
            </ul>
            <!--end::Sidebar Menu-->

            <!-- Docs CTA (bottom of sidebar) -->
          
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>