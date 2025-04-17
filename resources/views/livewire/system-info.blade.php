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

                    <button type="button" wire:click="checkForUpdate" class="btn btn-primary"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Check for Updates</span>
                        <span wire:loading wire:target="checkForUpdate">
                            <span>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Checking for updates...
                            </span>
                        </span>
                    </button>

                </div>

                @if ($updateAvailable)
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
        <div class="tab-pane fade {{ $currentTab === 'backups' ? 'active show' : '' }}" id="backups" role="tabpanel"
            aria-labelledby="profile-tab">

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


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Print Order Receipt</title>
        <style>
            @page {
                size: 8.3in 11.7in;
            }

            @page {
                size: A4;
            }

            .page {
                page-break-after: always;
                padding: 20px;
            }

            .table-bg {
                border-collapse: collapse;
                width: 100%;
                font-size: 15px;
                text-align: center;
            }

            .th {
                border: 1px solid #000;
                padding: 10px;
            }

            .td {
                border: 1px solid #000;
                padding: 3px;
            }

            .ass td {
                border: 1px solid #000;
                margin: 0px;
            }


            /* Container styling */
            .assessment-guide {
                width: 100%;
                text-align: center;
                margin-top: 10px;
            }

            .assessment-guide h3 {
                font-size: 18px;
                margin-bottom: 10px;
            }

            /* Grid styling for two rows and three columns */
            .grades-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                justify-items: center;
                padding: 0 10px;
            }

            /* Styling for each grade card */
            .grade-card {
                border: 1px solid #000;
                padding: 8px;
                font-size: 14px;
                width: 100%;
                box-sizing: border-box;
                text-align: center;
            }

            /* Range and details styling */
            .grade-range {
                font-weight: bold;
            }

            .grade-details {
                font-style: italic;
            }


            @media print {
                @page {
                    margin: 0px;
                    margin-left: 20px;
                    margin-right: 20px;
                }
            }
        </style>
    </head>

    <body>
        <div id="page">
            <table style="width: 100%;text-align:center">

                @php
                    $settings = App\Models\SettingsModel::getSettingsData();
                @endphp

                <tr>
                    <td width="5%"></td>
                    <td width="15%">
                        <img style="width: 88px" src="{{ asset('storage/' . ($settings['logo'] ?? NO_IMAGE)) }}"
                            alt="logo">
                    </td>
                    <td width="60%" style="text-transform:uppercase" align="left" valign="top">
                        <h1 style="margin-bottom: 0px">
                            <code>
                                {{ !empty($settings['website_name']) ? $settings['website_name'] : 'business name goes here' }}
                            </code>
                        </h1>
                        <code>{{ !empty($settings['motto']) ? $settings['motto'] : 'business motto goes here' }}</code>
                    </td>
                    <td width="15%" align="left">
                        <code>
                            <h3 style="margin-bottom: 0px">
                                {{ !empty($settings['address']) ? $settings['address'] : 'address here' }}
                            </h3>
                            <h3 style="margin: 0px">
                                {{ !empty($settings['email']) ? $settings['email'] : 'email' }}
                            </h3>
                            <h3 style="margin: 0px">
                                {{ !empty($settings['phone']) ? $settings['phone'] : 'phone1' }}|{{ !empty($settings['phone2']) ? $settings['phone2'] : 'phone2' }}
                                </h4>
                        </code>
                    </td>
                    <td width="5%"></td>
                </tr>

            </table>

            <table style="width: 100%">
                <tr>
                    <td width="5%"></td>
                    <td width="70%">
                        <table style="width: 100%; margin-bottom:3px">
                            <tbody>
                                <tr>
                                    <td width="23%">Customer:</td>
                                    <td style="border-bottom: 1px solid;width:100% ">Walk-in Customer
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table style="width: 100%; margin-bottom:3px">
                            <tbody>
                                <tr>
                                    <td width="23%">Phone Number:</td>
                                    <td style="border-bottom: 1px solid;width:20% ">
                                        0200041225
                                    </td>
                                    <td align="right" width="20%">Email:</td>
                                    <td style="border-bottom: 1px solid;width:80% ">walkin@mail.com</td>
                                </tr>
                            </tbody>
                        </table>

                        <table style="width: 100%; margin-bottom:3px">
                            <tbody>
                                <tr>
                                    <td width="23%">Order Id:</td>
                                    <td style="border-bottom: 1px solid;width:20% ">1001991</td>
                                    <td align="right" width="20%">Order Date:</td>
                                    <td style="border-bottom: 1px solid;width:80% ">16-04-2025</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="5%"></td>
                </tr>
            </table>

            <br>
            
            <div>
                <table class="table-bg">
                    <thead>
                        <tr>
                            <td class="th" align="center" style="font-weight: bold;text-transform:uppercase"
                                colspan="9"><code>Order Receipt</code></td>
                        </tr>
                        <tr>
                            <th style="width: 10px" class="th" align="left">#</th>
                            <th class="th">Product Name</th>
                            <th style="width: 30px" class="th">Quantity</th>
                            <th style="width: 150px" class="th">Unit Price({{ $settings['currency'] ?? 'GHS' }})
                            </th>
                            <th style="width: 200px" class="th">Amount({{ $settings['currency'] ?? 'GHS' }})</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td class="td" align="left">100</td>
                            <td class="td" align="left">Product 1</td>
                            <td class="td">200</td>
                            <td class="td">20.50</td>
                            <td class="td">41</td>
                        </tr>


                        <tr>
                            <td class="th" align="left" colspan="4">Subtotal</td>
                            <td class="td">44</td>
                            </td>
                        </tr>
                        <tr>
                            <td class="th" align="left" colspan="4">Discount</td>

                            <td colspan="2" class="td">
                                0.00
                            </td>
                        </tr>
                        <tr>
                            <td class="th" align="left" colspan="4">Grand Total</td>
                            <td class="td">{{ $settings['currency'] ?? 'GHS' }}44</td>
                            </td>
                        </tr>
                        <tr></tr>
                    </tbody>
                </table>
            </div>

            <br>
        </div>
        <script>
            // window.print();
        </script>
    </body>

    </html>


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
