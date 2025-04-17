<div>

    <div class="row align-items-center">
        @php
            $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
        @endphp
        <div class="col-xl-12 col-lg-12 col-md-12 col-12">
            <div class="card rounded-bottom rounded-0 smooth-shadow-sm mb-5">

                <!-- nav -->
                <ul class="nav nav-lt-tab px-4" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'overview' ? 'active' : '' }}" id="overview-tab"
                            data-bs-toggle="tab" href="#" wire:click.prevent="switchTab('overview')" role="tab"
                            aria-controls="overview" aria-selected="true">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'backups' ? 'active' : '' }}" id="backups-tab"
                            data-bs-toggle="tab" href="#" wire:click.prevent="switchTab('backups')" role="tab"
                            aria-controls="backups" aria-selected="false">Backups</a>
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
        <div class="tab-pane fade {{ $currentTab === 'overview' ? 'active show' : '' }} " id="overview" role="tabpanel"
            aria-labelledby="home-tab">
            <div class="row">
                <div class="card">
                    <div class="card-header ">
                        <div class="d-flex justify-content-between  align-items-center">
                            <div>
                                <h4 class="mb-0">Overview</h4>
                            </div>
                        </div>


                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">

                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between  align-items-center">
                                    <div class="d-flex align-items-center">

                                        <div>
                                            <h5 class="mb-0 ">Current Version:</h5>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <p class="text-dark mb-0">{{ $currentVersion }}</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">

                                        <div>
                                            <h5 class="mb-0 ">Latest Version:</h5>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <p class="text-dark mb-0">{{ $latestVersion ?? 'Unknown' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item  px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">

                                        <div>
                                            <h5 class="mb-0 ">PHP Version:</h5>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <p class="text-dark mb-0">{{ phpversion() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item px-0 pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">

                                        <div>
                                            <h5 class="mb-0 ">OS:</h5>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <p class="text-dark mb-0">{{ PHP_OS }}</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item  px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">

                                        <div>
                                            <h5 class="mb-0 ">Developer:</h5>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <p class="text-dark mb-0">{{ __('Echo Edge Digital Solutions') }}</p>
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

                <div class="mt-3">

                    <button wire:click="checkForUpdate" wire:loading.attr="disabled" wire:target="checkForUpdate"
                        class="btn btn-primary position-relative">

                        <span wire:loading.remove wire:target="checkForUpdate">
                            Check for Updates
                        </span>
                        <!-- Loader Spinner -->
                        <span wire:loading wire:target="checkForUpdate"
                            class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true">
                        </span>
                    </button>
                </div>

                {{-- @if ($updateAvailable)
                    <div class="mt-3 alert alert-warning">
                        <strong>A new update (v{{ $latestVersion }}) is available!</strong> Would you like to install
                        it now?
                        <div class="mt-2">
                            <button wire:click="installUpdate" class="btn btn-success">
                                Install Update
                            </button>
                            <button class="btn btn-secondary" onclick="location.reload()">
                                Cancel
                            </button>
                        </div>
                    </div>
                @endif --}}

                @if (session('release_notes'))
                    <div class="alert alert-info mt-3">
                        <strong>Release Notes for Version {{ $latestVersion }}:</strong>
                        <p>{{ session('release_notes') }}</p>

                        <!-- Button to trigger SMS update request -->
                        <button wire:click="sendUpdateRequest" wire:loading.attr="disabled"
                            wire:target="sendUpdateRequest" class="btn btn-warning position-relative">

                            <span wire:loading.remove wire:target="sendUpdateRequest">
                                Request Update from Developers
                            </span>
                            <!-- Loader Spinner -->
                            <span wire:loading wire:target="sendUpdateRequest"
                                class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true">
                            </span>
                        </button>


                        <!-- Display success or error messages -->
                        @if ($successMessage)
                            <div class="alert alert-success mt-2">
                                {{ $successMessage }}
                            </div>
                        @endif

                        @if ($errorMessage)
                            <div class="alert alert-danger mt-2">
                                {{ $errorMessage }}
                            </div>
                        @endif
                    </div>
                @endif

                @if ($progress > 0)
                    <div class="mt-3">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                role="progressbar" style="width: {{ $progress }}%">
                                {{ $progress }}%
                            </div>
                        </div>
                    </div>
                @endif


                @if (session()->has('error'))
                    <div class="mt-3 alert alert-danger">
                        {{ session('error') }}
                    </div>
                @elseif (session()->has('success'))
                    <div class="mt-3 alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="tab-pane fade {{ $currentTab === 'backups' ? 'active show' : '' }}" id="backups"
            role="tabpanel" aria-labelledby="profile-tab">

            <div class="row">
                <div class="col-12">
                    <!-- card -->
                    <div class="card mb-4">
                        <div class="card-header  ">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" wire:model.live.debounce.500ms="search"
                                        class="form-control" placeholder="Search invoice number...">

                                </div>
                                <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                                    <button type="button" wire:click="backupAndUploadSQLite"
                                        class="btn btn-primary me-2" wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="backupAndUploadSQLite">
                                            + Create New Backup
                                        </span>
                                        <span wire:loading wire:target="backupAndUploadSQLite">
                                            <span class="spinner-border spinner-border-sm me-1" role="status"
                                                aria-hidden="true"></span>
                                            Creating...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table text-nowrap mb-0 table-centered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Filename</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($backups as $file)
                                            <tr>
                                                <td>{{ basename($file) }}</td>
                                                <td>
                                                    <button wire:click="download('{{ $file }}')"
                                                        class="btn btn-primary btn-sm">
                                                        Download
                                                    </button>
                                                    <button wire:click="delete('{{ $file }}')"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this backup?')">
                                                        Delete
                                                    </button>
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
            <div id="backupStatusAlert" class="alert d-none mt-3" role="alert"></div>
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

            {{-- {{ $logs->links() }} --}}

        </div>
    </div>


    {{-- <div>
        <h2>Files from Google Drive</h2>

        @if (is_array($files) && count($files) > 0)
            <ul>
                @foreach ($files as $file)
                    <li>
                        <strong>{{ $file['name'] }}</strong>
                        - <a href="{{ route('downloadFile', $file['id']) }}">Download</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No files found in Google Drive.</p>
        @endif
    </div> --}}


    @script
        <script>
            $wire.on('backup-status', (event) => {
                let myData = event.data;
                alert(myData.message)
                // Auto-hide after 4 seconds
                setTimeout(() => {
                    alertBox.classList.add('d-none');
                }, 4000);
            });
        </script>
    @endscript


</div>
