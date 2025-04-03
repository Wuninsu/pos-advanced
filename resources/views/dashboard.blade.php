@extends('layouts._main')
@section('content')
    <!-- Quick Links Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="#" class="btn btn-warning w-100 py-3 text-white">
                <i class="fa-solid fa-user-plus me-2"></i> Add User
            </a>
        </div>
        <div class="col-md-3">
            <a href="#" class="btn btn-warning w-100 py-3 text-white">
                <i class="fa-solid fa-box-open me-2"></i> Add Product
            </a>
        </div>
        <div class="col-md-3">
            <a href="#" class="btn btn-warning w-100 py-3 text-white">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Generate Report
            </a>
        </div>
        <div class="col-md-3">
            <a href="#" class="btn btn-warning w-100 py-3 text-white">
                <i class="fa-solid fa-cogs me-2"></i> Settings
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Widgets -->
        <div class="col-md-3">
            <div class="widget d-flex align-items-center">
                <i class="widget-icon fa-solid fa-users me-3"></i>
                <div>
                    <div class="widget-title">Total Users</div>
                    <div class="widget-number">1,234</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget widget d-flex align-items-center">
                <i class="widget-icon fa-solid fa-box me-3"></i>
                <div>
                    <div class="widget-title">Products</div>
                    <div class="widget-number">567</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget widget d-flex align-items-center">
                <i class="widget-icon fa-solid fa-receipt me-3"></i>
                <div>
                    <div class="widget-title">Transactions</div>
                    <div class="widget-number">$12,345</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget widget d-flex align-items-center">
                <i class="widget-icon fa-solid fa-truck me-3"></i>
                <div>
                    <div class="widget-title">Suppliers</div>
                    <div class="widget-number">78</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <h4>Sales Overview</h4>
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h4>User Growth</h4>
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>
@endsection
