 <div class="header">
     <div class="navbar-custom navbar navbar-expand-lg">
         <div class="container-fluid px-0">
             <a class="navbar-brand d-block d-md-none" href="/">
                 <img style="max-width: 30px !important" src="{{ asset('storage/' . ($settings['logo'] ?? NO_IMAGE)) }}"
                     alt="Image" />
             </a>

             <a id="nav-toggle" href="#!" class="ms-auto ms-md-0 me-0 me-lg-3">
                 <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                     class="bi bi-text-indent-left text-muted" viewBox="0 0 16 16">
                     <path
                         d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm.646 2.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L4.293 8 2.646 6.354a.5.5 0 0 1 0-.708zM7 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                 </svg>
             </a>

             <div class="d-none d-md-none d-lg-block col-md-6">
                 <livewire:forms.search-form />
             </div>


             <ul class="navbar-nav navbar-right-wrap ms-lg-auto d-flex nav-top-wrap align-items-center ms-4 ms-lg-0">
                 <li>
                     <div class="dropdown">
                         <button class="btn btn-ghost btn-icon rounded-circle" type="button" aria-expanded="false"
                             data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
                             <i class="bi theme-icon-active"></i>
                             <span class="visually-hidden bs-theme-text">Toggle theme</span>
                         </button>
                         <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bs-theme-text">
                             <li>
                                 <button type="button" class="dropdown-item d-flex align-items-center"
                                     data-bs-theme-value="light" aria-pressed="false">
                                     <i class="bi theme-icon bi-sun-fill"></i>
                                     <span class="ms-2">Light</span>
                                 </button>
                             </li>
                             <li>
                                 <button type="button" class="dropdown-item d-flex align-items-center"
                                     data-bs-theme-value="dark" aria-pressed="false">
                                     <i class="bi theme-icon bi-moon-stars-fill"></i>
                                     <span class="ms-2">Dark</span>
                                 </button>
                             </li>
                             <li>
                                 <button type="button" class="dropdown-item d-flex align-items-center active"
                                     data-bs-theme-value="auto" aria-pressed="true">
                                     <i class="bi theme-icon bi-circle-half"></i>
                                     <span class="ms-2">Auto</span>
                                 </button>
                             </li>
                         </ul>
                     </div>
                 </li>

                 <li>


                     <div class="notification-container" id="notificationContainer">

                     </div>
                     <div class="action-notification-container" id="actionNotificationContainer"></div>

                 </li>
                 <li class="dropdown stopevent ms-2">
                     <a class="btn btn-ghost btn-icon rounded-circle position-relative" href="#!" role="button"
                         id="dropdownNotification" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <i class="icon-xs" data-feather="bell"></i>
                         <span class="position-absolute top-0 start-25 translate-middle badge rounded-pill bg-danger"
                             id="notificationCount">
                             0
                         </span>
                     </a>

                     <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end"
                         aria-labelledby="dropdownNotification">
                         <div>
                             <div
                                 class="border-bottom px-3 pt-2 pb-3 d-flex justify-content-between align-items-center">
                                 <p class="mb-0 text-dark fw-medium fs-4">Notifications</p>
                                 <a href="#!" class="text-muted">
                                     <span>
                                         <i class="me-1 icon-xs" data-feather="settings"></i>
                                     </span>
                                 </a>
                             </div>
                             <div data-simplebar style="height: 250px">
                                 {{-- <ul class="list-group list-group-flush notification-list-scroll">

                                     <li class="list-group-item bg-light">
                                         <a href="#!" class="text-muted">
                                             <h5 class="mb-1">Rishi Chopra</h5>
                                             <p class="mb-0">Mauris blandit erat id nunc blandit, ac eleifend
                                                 dolor pretium.</p>
                                         </a>
                                     </li>

                                 </ul> --}}
                                 <ul class="list-group list-group-flush notification-list-scroll" id="notificationList">
                                     <!-- Items will be injected here dynamically -->
                                 </ul>
                             </div>
                             <div class="border-top px-3 py-2 text-center">
                                 <a href="#!" class="text-inherit">View all Notifications</a>
                             </div>
                         </div>
                     </div>
                 </li>


                 <!-- List -->
                 <li class="dropdown ms-2">
                     <a class="rounded-circle" href="#!" role="button" id="dropdownUser" data-bs-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false">
                         <div class="avatar avatar-md avatar-indicators avatar-online">
                             <img alt="avatar" src="{{ asset('storage/' . (auth()->user()->avatar ?? NO_IMAGE)) }}"
                                 class="rounded-circle" />
                         </div>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                         <div class="px-4 pb-0 pt-2">
                             <div class="lh-1">
                                 <h5 class="mb-1">{{ auth()->user()->username }}</h5>
                                 <a href="{{ route('users.profile', ['user' => auth()->user()->uuid]) }}"
                                     class="text-inherit fs-6">View my profile</a>
                             </div>
                             <div class="dropdown-divider mt-3 mb-2"></div>
                         </div>

                         <ul class="list-unstyled">
                             <li>
                                 <a class="dropdown-item d-flex align-items-center"
                                     href="{{ route('users.edit', ['user' => auth()->user()->uuid]) }}">
                                     <i class="me-2 icon-xxs dropdown-item-icon" data-feather="user"></i>
                                     Edit Profile
                                 </a>
                             </li>
                             {{-- <li>
                                        <a class="dropdown-item" href="#!">
                                            <i class="me-2 icon-xxs dropdown-item-icon" data-feather="activity"></i>
                                            Activity Log
                                        </a>
                                    </li> --}}

                             {{-- <li>
                                        <a class="dropdown-item d-flex align-items-center" href="#!">
                                            <i class="me-2 icon-xxs dropdown-item-icon" data-feather="settings"></i>
                                            Settings
                                        </a>
                                    </li> --}}
                             <li>
                                 <a class="dropdown-item" href="{{ route('logout') }}">
                                     <i class="me-2 icon-xxs dropdown-item-icon" data-feather="power"></i>
                                     Sign Out
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>
             </ul>
         </div>
     </div>
 </div>
