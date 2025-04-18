  <div class="app-menu">
      <div class="navbar-vertical navbar nav-dashboard">
          <div class="h-100" data-simplebar>
              <!-- Brand logo -->
              <a class="navbar-brand" href="/">
                  <img style="max-width: 200px !important" src="{{ asset('storage/' . ($settings['logo'] ?? NO_IMAGE)) }}"
                      alt="" />
              </a>
              <!-- Navbar nav -->
              <ul class="navbar-nav flex-column" id="sideNavbar">
                  <li class="nav-item">
                      <a class="nav-link has-arrow {{ request()->is('/') ? 'active' : '' }}"
                          href="{{ route('dashboard') }}">
                          <i data-feather="home" class="nav-icon me-2 icon-xxs"></i>
                          Dashboard
                      </a>
                  </li>
                  @if (in_array(auth()->user()->role, ['admin', 'manager']))
                      <li class="nav-item">
                          <a class="nav-link has-arrow {{ request()->is('users*') ? 'active' : '' }}"
                              href="{{ route('users') }}">
                              <i data-feather="users" class="nav-icon me-2 icon-xxs"></i>
                              Users
                          </a>
                      </li>
                  @endif
                  <li class="nav-item">
                      <a class="nav-link has-arrow {{ request()->is('customers*') ? 'active' : '' }}"
                          href="{{ route('customers') }}">
                          <i data-feather="smile" class="nav-icon me-2 icon-xxs"></i>
                          Customers
                      </a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link has-arrow {{ request()->is('categories*') ? 'active' : '' }}"
                          href="{{ route('categories') }}">
                          <i data-feather="tag" class="nav-icon me-2 icon-xxs"></i>
                          Categories
                      </a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link has-arrow @if (in_array(request()->segment(1), ['suppliers'])) active @endif "
                          href="{{ route('suppliers') }}">
                          <i data-feather="truck" class="nav-icon me-2 icon-xxs"></i>
                          Suppliers
                      </a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link has-arrow {{ request()->is('products*') ? 'active' : '' }}"
                          href="{{ route('products') }}">
                          <i data-feather="box" class="nav-icon me-2 icon-xxs"></i>
                          Products
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link has-arrow {{ request()->is(['orders*', 'order-detail']) ? 'active' : '' }}"
                          href="{{ route('orders') }}">
                          <i data-feather="file-text" class="nav-icon me-2 icon-xxs"></i>
                          Orders
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link has-arrow {{ request()->is('invoices*') ? 'active' : '' }}"
                          href="{{ route('invoices') }}">
                          <i data-feather="credit-card" class="nav-icon me-2 icon-xxs"></i>
                          invoices
                      </a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link has-arrow {{ request()->is('pos*') ? 'active' : '' }}"
                          href="{{ route('pos') }}">
                          <i data-feather="shopping-cart" class="nav-icon me-2 icon-xxs"></i>
                          POS
                      </a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link  collapsed" href="#!" data-bs-toggle="collapse"
                          data-bs-target="#navlayoutPage"
                          aria-expanded="@if (in_array(request()->segment(2), ['config', 'profile'])) true @else false @endif"
                          aria-controls="navlayoutPage">
                          <i class="nav-icon me-2 icon-xxs" data-feather="settings"></i>
                          Settings
                      </a>
                      <div id="navlayoutPage" class="collapse @if (in_array(request()->segment(2), ['config', 'profile', 'system-info'])) show @else @endif"
                          data-bs-parent="#sideNavbar">
                          <ul class="nav flex-column">
                              @if (in_array(auth()->user()->role, ['admin', 'manager']))
                                  <li class="nav-item">
                                      <a class="nav-link @if (in_array(request()->segment(2), ['config'])) active @endif"
                                          href="{{ route('settings') }}">Config</a>
                                  </li>
                                  <li class="nav-item">
                                      <a class="nav-link @if (in_array(request()->segment(2), ['system-info'])) active @endif"
                                          href="{{ route('settings.sys-info') }}">System Info</a>
                                  </li>
                                  <li class="nav-item">
                                      <a class="nav-link @if (in_array(request()->segment(2), ['preferences'])) active @endif"
                                          href="{{ route('settings.preferences') }}">Preferences</a>
                                  </li>
                              @endif

                              <li class="nav-item">
                                  <a class="nav-link @if (in_array(request()->segment(2), ['profile'])) active @endif "
                                      href="{{ route('users.profile', ['user' => auth()->user()->uuid]) }}">Profile</a>
                              </li>
                          </ul>
                      </div>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link has-arrow text-danger" href="{{ route('logout') }}">
                          <i data-feather="lock" class="nav-icon me-2 icon-xxs"></i>
                          Logout
                      </a>
                  </li>

              </ul>
          </div>
      </div>
  </div>
