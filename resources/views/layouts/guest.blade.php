<!DOCTYPE html>
<html lang="en">

<head>
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
    <link href="{{ asset('assets/css/style2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Add this in your <head> section if FontAwesome is not included yet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

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

    <style>
        /* Global scrollbar styling for WebKit Browsers (Chrome, Edge, Safari) */
        ::-webkit-scrollbar {
            width: 6px;
            /* Adjust scrollbar width */
            height: 6px;
            /* Adjust scrollbar height */
            background-color: #f1f1f1;
            /* Scrollbar track color */
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888;
            /* Scrollbar thumb color */
            border-radius: 4px;
            /* Optional: Rounded edges for the thumb */
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #555;
            /* Darker thumb color on hover */
        }

        /* Global scrollbar styling for Firefox */
        * {
            scrollbar-width: thin;
            /* Reduces scrollbar width */
            scrollbar-color: #1f064d #f1f1f1;
            /* Thumb color and track color */
        }

        .thin-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .thin-scrollbar::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }

        .thin-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        .thin-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #888 #f1f1f1;
        }

        .scrollable {
            /* max-height: 50px; */
            width: 100%;
            overflow-x: scroll;
            overflow-y: hidden;
            white-space: nowrap;
            display: block;
            padding: 5px 10px;
            border: 1px solid #ddd;
        }

        /* Container Styles */
        .payment-label {
            font-size: 1.125rem;
            /* text-lg */
            font-weight: 500;
            /* font-medium */
            color: #4b5563;
            /* text-gray-700 */
            margin-bottom: 0.75rem;
        }

        .payment-options {
            display: flex;
            gap: 1rem;
        }

        /* Radio Item Styles */
        .radio-item {
            position: relative;
            display: flex;
            align-items: center;
        }

        /* Hide the default radio input */
        .radio-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        /* Label Styles */
        .radio-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            /* text-sm */
            color: #6b7280;
            /* text-gray-600 */
            background-color: #fff;
            /* bg-white */
            border: 1px solid #d1d5db;
            /* border-gray-300 */
            border-radius: 0.375rem;
            /* rounded-md */
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            /* shadow-sm */
        }

        /* Hover and Focus States */
        .radio-label:hover {
            border-color: #9ca3af;
            /* border-gray-400 */
        }

        /* Checked State */
        .radio-input:checked+.radio-label {
            border-color: green;
            /* border-blue-500 */
            background-color: green;
            /* bg-blue-50 */
            color: white;
            /* text-blue-500 */
        }

        /* Icon Styles */
        .radio-label i {
            font-size: 1rem;
            /* Ensure icons align well */
        }



        .card {
            cursor: pointer;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            /* Smoother transition */
            height: 350px;
            /* Fixed height for product cards */
        }

        /* Main Product Card Styling */
        .product-card {
            transition: transform 0.5s ease, box-shadow 0.5s ease;
            border-radius: 10px;
            overflow: hidden;
            box-sizing: border-box;
            border-bottom: 2px solid red;
            border-top: 2px solid red;
        }


        .product-card:hover {
            transform: translateY(-10px);
        }

        /* Scrollable Product List */
        .product-list {
            max-height: 450px;
            height: 450px;
            overflow-y: auto;
            padding: 0px;
            margin: 0px;
            width: 100%;
        }



        /* Card Layout Fix */
        .product-card .card {
            height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0px;
        }

        /* Image Styling */
        .product-card img {
            width: 100%;
            /* Ensures image does not exceed card width */
            max-height: 100px;
            object-fit: cover;
            margin-bottom: 5px;

        }

        /* Adjust Card Body Padding */
        .product-card .card-body {
            padding: 8px;
            text-align: center;
        }

        /* Button Padding Fix */
        .product-card button {
            padding: 6px 10px;
            /* Slightly more padding for better clickability */
            font-size: 14px;
        }

        /* Adjust Title Size */
        .product-card .card-title {
            font-size: 15px;
            margin-bottom: 5px;
        }


        /* Scrollbar styling */
        .product-list::-webkit-scrollbar {
            width: 5px;
        }

        .product-list::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }

        .product-list::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        .nav-icons {
            margin-right: 1rem;
        }

        /* Scrollable cart summary */
        #cart-items {
            max-height: 400px;
            height: 400px;
            /* Set the maximum height for the scrollable cart area */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        .cart-item {
            height: 60px;
            /* Fixed height for cart items */
        }

        .category-buttons {
            flex-wrap: wrap;
            /* Allow wrapping in smaller screens */
        }

        .category-buttons .btn {
            margin: 0.25rem;
            /* Margin for better spacing */
        }

        /* Apply to the whole page (for Webkit browsers like Chrome, Safari, Edge) */
        ::-webkit-scrollbar {
            width: 12px;
            /* Adjust width of vertical scrollbar */
            height: 12px;
            /* Adjust height of horizontal scrollbar */
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Scrollbar track color */
            border-radius: 10px;
            /* Optional: rounded corners */
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888;
            /* Scrollbar thumb color */
            border-radius: 10px;
            /* Optional: rounded thumb */
            border: 3px solid #f1f1f1;
            /* Optional: adds spacing around the thumb */
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #555;
            /* Darker color on hover */
        }

        /* Firefox specific scrollbar styling */

        .scroll-container {
            scrollbar-width: 20px;
            /* Thin scrollbar width */
            scrollbar-color: #888 #f1f1f1;
            /* Thumb and track colors */
        }


        .custom-btn {
            background-color: #04284c;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .custom-btn:hover {
            background-color: #031f33;
            /* Slightly darker shade */
            transform: translateY(-2px);
        }

        .custom-btn:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(4, 40, 76, 0.5);
        }


        /* Thermal Printer Optimized Receipt Styles */
        #thermal-receipt {
            font-family: 'Courier New', Courier, monospace;
            /* Best for thermal readability */
            font-size: 12px;
            width: 80mm;
            /* Thermal printer width */
            margin: 0 auto;
            color: #000;
            text-align: left;
        }

        /* Header Section */
        .receipt-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .receipt-header img {
            max-height: 50px;
            /* Optimized for thermal printing */
            margin-bottom: 5px;
        }

        .receipt-header h1 {
            font-size: 14px;
            margin: 0;
        }

        .receipt-header p {
            margin: 0;
            line-height: 1.2em;
        }


        .customer-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .customer-info td {
            padding: 2px 0;
            font-size: 12px;
            vertical-align: top;
        }

        /* Remove borders completely */
        .customer-info tr {
            border: none;
        }

        .customer-info td {
            border: none;
        }


        /* Info Section */
        .receipt-info {
            margin-bottom: 10px;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
        }


        /* Table Section */
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .receipt-table th,
        .receipt-table td {
            text-align: left;
            padding: 2px 0;
        }

        .receipt-table th {
            font-size: 12px;
            border-bottom: 1px dashed #000;
        }

        .receipt-table td {
            font-size: 12px;
        }

        /* Footer Section */
        .receipt-footer {
            text-align: center;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        .receipt-footer p {
            margin: 0;
            line-height: 1.2em;
            font-size: 12px;
        }

        /* Print-Specific Styles */
        @media print {
            #thermal-receipt {
                width: 80mm;
                /* Fit thermal printer width */
                margin: 0;
                padding: 0;
            }

            @page {
                margin: 0;
            }
        }

        @media print {
            #printable {
                /* width: 100%; */
                width: 80mm;
                height: auto;
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
                margin: 0 auto;
            }

            body {
                margin: 0;
                padding: 0;
            }

            #printable * {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <main class="container-fluid">
        <div class="my-2">
            {{ $slot }}
        </div>
    </main>
    <!-- Scripts -->
    <!-- Libs JS -->
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/dist/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/print.js') }}"></script>
    <!-- Theme JS -->
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>
</body>

</html>
