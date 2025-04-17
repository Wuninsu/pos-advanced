<div>
    <div class="row align-items-center">
        @php
            $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
        @endphp
        <div class="col-xl-12 col-lg-12 col-md-12 col-12">
            <div class="card rounded-bottom rounded-0 smooth-shadow-sm mb-5">
                <div class="d-flex align-items-center justify-content-between pt-4 pb-6 px-4">
                    <div class="d-flex align-items-center">
                        <!-- avatar -->
                        <div
                            class="avatar-xxl avatar-indicators avatar-online me-2 position-relative d-flex justify-content-end align-items-end">
                            <img src="{{ asset('storage/' . ($profile->avatar ?? NO_IMAGE)) }}"
                                class="avatar-xxl rounded-circle border border-2 " alt="Image">
                        </div>
                        <!-- text -->
                        <div class="lh-1">
                            <h2 class="mb-0">
                                {{ $profile->name }}
                                <a href="#!" class="text-decoration-none">
                                </a>
                            </h2>
                            <p class="mb-0 d-block"> {{ $profile->email }}</p>
                            <p class="mb-0 d-block">
                                @if ($profile->role === 'admin')
                                    <span class="badge badge-success-soft text-success">{{ $profile->role }}</span>
                                @elseif ($profile->role === 'manager')
                                    <span class="badge badge-primary-soft text-primary">{{ $profile->role }}</span>
                                @elseif ($profile->role === 'cashier')
                                    <span class="badge badge-danger-soft text-danger">{{ $profile->role }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('users.edit', ['user' => $profile->uuid]) }}"
                            class="btn btn-outline-primary d-none d-md-block">Edit Profile</a>
                    </div>
                </div>
                <!-- nav -->
                <ul class="nav nav-lt-tab px-4" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'overview' ? 'active' : '' }}" id="overview-tab"
                            data-bs-toggle="tab" href="#" wire:click.prevent="switchTab('overview')"
                            role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'orders' ? 'active' : '' }}" id="orders-tab"
                            data-bs-toggle="tab" href="#" wire:click.prevent="switchTab('orders')" role="tab"
                            aria-controls="orders" aria-selected="false">Orders Processed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'my-logs' ? 'active' : '' }}" id="my-logs-tab"
                            data-bs-toggle="tab" href="#" wire:click.prevent="switchTab('my-logs')" role="tab"
                            aria-controls="my-logs" aria-selected="false">Activity Logs</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>



    <div class="tab-content p-4" id="myTabContent">
        <div class="tab-pane fade {{ $currentTab === 'overview' ? 'active show' : '' }} " id="overview"
            role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="col-12 mb-5">
                    <div class="card">
                        <div class="card-header ">
                            <div class="d-flex justify-content-between  align-items-center">
                                <div>
                                    <h4 class="mb-0">Profile Overview</h4>
                                </div>
                            </div>


                        </div>
                        <div class="card-body">
                            <p></p>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between  align-items-center">
                                        <div class="d-flex align-items-center">

                                            <div>
                                                <h5 class="mb-0 ">User Role:</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0">
                                                    @if ($profile->role === 'admin')
                                                        <span
                                                            class="badge badge-success-soft fs-4 text-success">{{ $profile->role }}</span>
                                                    @elseif ($profile->role === 'manager')
                                                        <span
                                                            class="badge badge-warning-soft text-warning">{{ $profile->role }}</span>
                                                    @elseif ($profile->role === 'cashier')
                                                        <span
                                                            class="badge badge-danger-soft text-danger">{{ $profile->role }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between  align-items-center">
                                        <div class="d-flex align-items-center">

                                            <div>
                                                <h5 class="mb-0 ">Account Created On:</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0">
                                                    {{ date('jS M Y', strtotime($profile->created_at ?? '')) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">

                                            <div>
                                                <h5 class="mb-0 ">Username</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0">{{ $profile->username ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item  px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">

                                            <div>
                                                <h5 class="mb-0 ">Full Name</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0">{{ $profile->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item px-0 pb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">

                                            <div>
                                                <h5 class="mb-0 ">Email</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0">{{ $profile->email ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item  px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">

                                            <div>
                                                <h5 class="mb-0 ">Phone Number</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0">{{ $profile->phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item px-0 pb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">

                                            <div>
                                                <h5 class="mb-0 ">Address</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0">{{ $profile->address ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 mb-5">
                    <!-- card -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Orders Processed By User </h4>
                            <a href="#" wire:click.prevent="switchTab('orders')"
                                class="btn btn-primary btn-sm">View Details</a>

                        </div>
                        <!-- card body -->

                        <!-- row -->
                        <div class="row">

                            <!-- col -->
                            <div class="col-lg-4 col-md-12 col-12">
                                <div class="d-flex align-items-center justify-content-between p-4">
                                    <div>
                                        <h2 class="h1  mb-0">
                                            {{ $settings['currency'] }}{{ $orderInfo['pending']['amount'] }}</h2>
                                        <p class="mb-0">Pending </p>

                                    </div>
                                    <div class="ms-3">
                                        <div
                                            class="icon-shape icon-lg bg-warning-soft text-warning rounded-circle text-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-dollar-sign icon-sm">
                                                <line x1="12" y1="1" x2="12" y2="23">
                                                </line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- col -->
                            <div class="col-lg-4 col-md-12 col-12 border-start-md">
                                <div class="d-flex align-items-center justify-content-between p-4">
                                    <div>
                                        <h2 class="h1  mb-0">
                                            {{ $settings['currency'] }}{{ $orderInfo['canceled']['amount'] }}</h2>
                                        <p class="mb-0">Canceled</p>

                                    </div>
                                    <div class="ms-3">
                                        <div class="icon-shape icon-lg bg-danger-soft text-danger rounded-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-shopping-cart icon-sm">
                                                <circle cx="9" cy="21" r="1"></circle>
                                                <circle cx="20" cy="21" r="1"></circle>
                                                <path
                                                    d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                                </path>
                                            </svg>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <!-- col -->
                            <div class="col-lg-4 col-md-12 col-12 border-start-md">
                                <div class="d-flex align-items-center justify-content-between p-4">
                                    <div>
                                        <h2 class="h1  mb-0">
                                            {{ $settings['currency'] }}{{ $orderInfo['completed']['amount'] }}
                                        </h2>
                                        <p class="mb-0">Completed</p>

                                    </div>
                                    <div class="ms-3">
                                        <div class="icon-shape icon-lg bg-success-soft text-success rounded-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-pie-chart icon-sm">
                                                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                                <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                            </svg>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-8 col-md-12 col-12 mb-5">
                        <!-- card  -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Orders Processed By User </h4>
                                <a href="#" wire:click.prevent="switchTab('orders')"
                                    class="btn btn-primary btn-sm">View Details</a>

                            </div>
                            <div class="card-body">


                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-12 col-12 mb-5">
                        <!-- card  -->
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-0 ">Task Completion Status</h4>
                                </div>
                                <div>
                                    <!-- dropdown  -->
                                    <span class="dropdown dropstart">
                                        <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#!"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-more-vertical icon-xs">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="12" cy="5" r="1"></circle>
                                                <circle cx="12" cy="19" r="1"></circle>
                                            </svg>
                                        </a>
                                        <span class="dropdown-menu">

                                            <a class="dropdown-item d-flex align-items-center"
                                                href="#!">Action</a>
                                            <a class="dropdown-item d-flex align-items-center" href="#!">Another
                                                action</a>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="#!">Something
                                                else
                                                here</a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <!-- card body  -->
                            <div class="card-body">
                                <canvas id="orderChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade {{ $currentTab === 'orders' ? 'active show' : '' }}" id="orders"
            role="tabpanel" aria-labelledby="profile-tab">

            <div class="row">
                <div class="col-12">
                    <!-- card -->
                    <div class="card mb-4">
                        <div class="card-header  ">
                            <div class="row">
                                <div class=" col-lg-3 col-md-6">
                                    <input type="search" wire:model.live.debounce.500ms="search"
                                        class="form-control" placeholder="Search invoice number...">

                                </div>
                                <div class="col-lg-4 col-md-6 d-flex align-items-center mt-3 mt-md-0">
                                    <label class="form-label me-2 mb-0">Status</label>
                                    <select wire:model.live="status" class="form-select"
                                        aria-label="Default select example">
                                        <option value="" selected>status...</option>
                                        <option value="pending">Pending</option>
                                        <option value="completed">Completed</option>
                                        <option value="canceled">Canceled</option>
                                    </select>
                                </div>




                                <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                                    <a href="{{ route('pos') }}" class="btn btn-primary me-2">+ POS</a>
                                    <a href="#!" class="btn btn-light ">Export</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table text-nowrap mb-0 table-centered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="pe-0">Order ID</th>
                                            <th>Customer</th>
                                            <th>Order Amount({!! $settings['currency'] ?? 'Ghs' !!})</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $order)
                                            <tr>
                                                <td>#{{ $order->order_number }}</td>
                                                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                                <td>{{ $order->order_amount }}</td>
                                                <td>
                                                    @if ($order->status === 'completed')
                                                        <span
                                                            class="badge badge-success-soft text-success">Completed</span>
                                                    @elseif ($order->status === 'pending')
                                                        <span
                                                            class="badge badge-warning-soft text-warning">Pending</span>
                                                    @elseif ($order->status === 'canceled')
                                                        <span
                                                            class="badge badge-danger-soft text-danger">Pending</span>
                                                    @endif
                                                </td>
                                                <td>{{ date('jS M Y', strtotime($order->created_at)) }}</td>
                                                <td>

                                                    <a href="{{ route('orders.details', ['order' => $order->order_number]) }}"
                                                        class="btn btn-primary btn-sm"><i data-feather="eye"
                                                            class="icon-xs"></i> View</a>
                                                    <button type="button"
                                                        wire:click="confirmDelete({{ $order->id }})"
                                                        class="btn btn-sm btn-danger"> <i data-feather="trash-2"
                                                            class="icon-xs"></i> Delete</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">
                                                    <span class="text-danger">No records found</span>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mx-3">
                                    {{-- @isset($orders)
                                        {{ $orders->links() }}
                                    @endisset --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="tab-pane fade {{ $currentTab === 'my-logs' ? 'active show' : '' }}" id="my-logs"
            role="tabpanel" aria-labelledby="contact-tab">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Event</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Details</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->user?->name ?? 'Guest' }}</td>
                                <td>{{ $log->event }}</td>
                                <td>{{ $log->ip_address }}</td>
                                <td>{{ $log->user_agent }}</td>
                                <td>{{ $log->details }}</td>
                                <td>{{ $log->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $logs->links() }}

        </div>
    </div>


    {{-- @script --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('orderChart').getContext('2d');

            const chartData = @json($orderInfo);

            const labels = ["Pending", "Canceled", "Completed"];
            const data = [
                chartData.pending.count,
                chartData.canceled.count,
                chartData.completed.count
            ];

            const backgroundColors = ["#FFC107", "#DC3545", "#28A745"];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
    {{-- @endscript --}}
</div>
