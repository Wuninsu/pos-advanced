<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="Codescandy" />


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ !empty($title) ? $title : '' }} - {{ config('app.name', 'Laravel') }}</title>
    @php
        $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
    @endphp

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . ($settings['favicon'] ?? NO_IMAGE)) }}" />

    <!-- Color modes -->
    <script src="{{ asset('assets/js/vendors/color-modes.js') }}"></script>

    <!-- Libs CSS -->
    <link href="{{ asset('assets/libs/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/libs/%40mdi/font/css/materialdesignicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/libs/simplebar/dist/simplebar.min.css') }}" rel="stylesheet" />


    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme.min.css') }}">
    <link href="{{ asset('assets/libs/dropzone/dist/dropzone.css') }}" rel="stylesheet">


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-M8S4MT3EYG"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-M8S4MT3EYG');
    </script>

    <!-- JavaScript -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css" />
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css" />
    <!-- Semantic UI theme -->
    <style>
        /* Notification container */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Notification style */
        .notification {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(-10px);
            animation: fadeIn 0.5s forwards;
            width: 380px;
            position: relative;
        }

        /* Notification title */
        .notification-title {
            font-weight: bold;
            font-size: 14px;
        }

        /* Notification content */
        .notification-content {
            font-weight: bold;
            font-size: 12px;
            margin-top: 5px;
        }



        /* Buttons container */
        .notification-buttons {
            margin-top: 10px;
            display: flex;
            justify-content: end;
            gap: 2px;
        }

        /* Close button */
        .notification-container .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            background-color: rgb(214, 49, 19);

            /* Make it rounded */
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;

            /* Add hover effect */
            transition: background 0.3s ease;
        }

        .notification-container .close-btn:hover {
            background-color: rgb(34, 34, 33);
            /* Slightly darker on hover */
        }




        /* Fade in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Fade out animation */
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
    </style>


    <style>
        /* Backdrop */
        .backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            display: none;
            /* Hidden by default */
            justify-content: center;
            align-items: center;
        }

        /* Show backdrop when active */
        .backdrop.active {
            display: flex;
        }

        /* Centered Delete Confirmation Modal */
        .confirmDelete {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: scale(0.9);
            animation: fadeIn 0.3s forwards;
            width: 400px;
            text-align: center;
            position: relative;
        }

        /* Title */
        .confirmDelete-title {
            font-weight: bold;
            font-size: 16px;
        }

        /* Content */
        .confirmDelete-content {
            font-size: 14px;
            margin-top: 10px;
        }

        /* Buttons */
        .confirmDelete-buttons {
            margin-top: 15px;
            display: flex;
            justify-content: end;
            gap: 10px;
        }



        .confirmDelete-buttons .cancel {
            background: #ccc;
            color: #333;
        }

        .confirmDelete-buttons .confirm {
            background: rgb(214, 49, 19);
            color: white;
        }

        /* Close button */
        .confirmDelete .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgb(214, 49, 19);
            color: #fff;
            border: none;
            font-size: 16px;
            cursor: pointer;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .confirmDelete .close-btn:hover {
            background: rgb(34, 34, 33);
        }

        /* Fade in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Fade out animation */
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: scale(1);
            }

            to {
                opacity: 0;
                transform: scale(0.9);
            }
        }
    </style>

    <style>
        .search-container {
            position: relative;
            width: 100%;
            /* max-width: 400px; */
            margin: auto;
        }

        .search-container .dropdown-menu {
            width: 100%;
            display: block;
            max-height: 400px;
            overflow-y: auto;
            z-index: 10;
        }

        .search-container .list-group-item {
            cursor: pointer;
        }

        .search-container .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <main id="main-wrapper" class="main-wrapper">

        @include('layouts.navbar')
        <!-- side vertical -->
        @include('layouts.sidebar')

        <!-- Page content -->
        <div id="app-content">
            <div class="app-content-area">
                <div class="container-fluid ">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            @include('layouts._alerts')
                            {{-- <p id="timer">Waiting for updates...</p>
                            <button onclick="showNotification('Delete Request Was Sent', 'Product Name 200 4990')">Show
                                Notification</button>

                            <button
                                onclick="showActionNotification('Action Required', 'Do you approve this request?')">Show
                                Action Notification</button> --}}

                            <div class="d-flex justify-content-between align-items-center mb-5">
                                <h3 class="mb-0 ">{{ $title ?? '' }}</h3>
                                <a href="javascript:history.back()" class="btn btn-dark btn-sm">Back</a>
                            </div>
                        </div>
                    </div>
                    {{-- @dd(request()->segment(2)) --}}
                    {{ $slot }}


                    <script>
                        function showActionNotification(title, content) {
                            const container = document.getElementById('notificationContainer');
                            const notification = document.createElement('div');
                            notification.classList.add('notification');

                            // Add title, content, and buttons
                            notification.innerHTML = `
                            <button class="close-btn" onclick="this.parentElement.remove()">×</button>
                            <div class="notification-title">${title}</div>
                            <div class="notification-content">${content}</div>
                            <div class="notification-buttons">
                                <button class="btn btn-success btn-sm" onclick="handleActionApproval(this)">Approve</button>
                                <button class="btn btn-danger btn-sm" onclick="handleActionRejection(this)">Reject</button>
                            </div>
                             `;

                            container.appendChild(notification);
                        }

                        function handleActionApproval(btn) {
                            alert("Action Approved!");
                            btn.closest('.action-notification').remove();
                        }

                        function handleActionRejection(btn) {
                            alert("Action Rejected!");
                            btn.closest('.action-notification').remove();
                        }
                    </script>


                    <div id="deleteModal" class="backdrop">
                        <div class="confirmDelete">
                            <button class="close-btn" onclick="closeModal()">×</button>
                            <div class="confirmDelete-title">Delete Confirmation</div>
                            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this
                                item?</div>
                            <div class="confirmDelete-buttons">
                                <button class="btn btn-secondary btn-sm" onclick="closeModal()">Cancel</button>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete()">Delete</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->

    <!-- Libs JS -->

    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/dist/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>

    <!-- Theme JS -->
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>

    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>

    <!-- popper js -->
    <script src="{{ asset('assets/libs/%40popperjs/core/dist/umd/popper.min.js') }}"></script>
    <!-- tippy js -->
    <script src="{{ asset('assets/libs/tippy.js/dist/tippy-bundle.umd.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/tooltip.js') }}"></script>

    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/charts.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/print.js') }}"></script>
    {{--
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        function closeModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
    </script>


    {{-- <script>
       

        $(document).ready(function() {



            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;

            var pusher = new Pusher('4db2f2a0d798f72ff1b8', {
                cluster: 'mt1'
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('delete-request', function(data) {
                alert(JSON.stringify(data));
            });
        });
    </script> --}}
</body>

</html>
