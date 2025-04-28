<div>

    <style>
        .custom-report-table th,
        .custom-report-table td {
            vertical-align: middle !important;
            font-size: 16px;
        }

        .custom-report-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        #printableArea2 h3.report-title {
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        .card {
            border-radius: 10px;
            border: none;
        }

        .btn-outline-success {
            border-radius: 20px;
        }

        .custom-report-table th,
        .custom-report-table td {
            vertical-align: middle !important;
            font-size: 16px;
        }

        .custom-report-table th {
            font-weight: bold;
        }

        .card {
            border-radius: 12px;
            border: none;
        }

        .btn-outline-success {
            border-radius: 30px;
        }

        .badge {
            padding: 0.6em;
            font-size: 12px;
            border-radius: 10px;
        }
    </style>
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="row g-2 mb-3">
                    <div class="col-auto">
                        <button type="button" wire:click="loadDebtorsReport" wire:loading.attr="disabled"
                            wire:target="loadDebtorsReport" class="btn btn-primary position-relative">
                            <span wire:loading.remove wire:target="loadDebtorsReport">
                                Load Debtors Report
                            </span>
                            <span wire:loading wire:target="loadDebtorsReport">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading Data...
                            </span>
                        </button>

                        <button type="button" wire:click="loadSalesTodayReport" wire:loading.attr="disabled"
                            wire:target="loadSalesTodayReport" class="btn btn-info position-relative">
                            <span wire:loading.remove wire:target="loadSalesTodayReport">
                                Load Sales Today Report
                            </span>
                            <span wire:loading wire:target="loadSalesTodayReport">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading Data...
                            </span>
                        </button>

                        <button type="button" wire:click="loadTopSellingProductsReport" wire:loading.attr="disabled"
                            wire:target="loadTopSellingProductsReport" class="btn btn-success position-relative">
                            <span wire:loading.remove wire:target="loadTopSellingProductsReport">
                                Load Top Selling Products
                            </span>
                            <span wire:loading wire:target="loadTopSellingProductsReport">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading Data...
                            </span>
                        </button>

                        <button type="button" wire:click="loadSalesSummaryReport" wire:loading.attr="disabled"
                            wire:target="loadSalesSummaryReport" class="btn btn-warning position-relative">
                            <span wire:loading.remove wire:target="loadSalesSummaryReport">
                                Load Sales Summary
                            </span>
                            <span wire:loading wire:target="loadSalesSummaryReport">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading Data...
                            </span>
                        </button>

                        <button type="button" wire:click="loadLowStockProductsReport" wire:loading.attr="disabled"
                            wire:target="loadLowStockProductsReport" class="btn btn-danger position-relative">
                            <span wire:loading.remove wire:target="loadLowStockProductsReport">
                                Low Stock Products
                            </span>
                            <span wire:loading wire:target="loadLowStockProductsReport">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading Data...
                            </span>
                        </button>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Start Date</label>
                        <input type="date" wire:model="start_date"
                            class="form-control @error('start_date') border-danger is-invalid @enderror">
                        @error('start_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>End Date</label>
                        <input type="date" wire:model="end_date"
                            class="form-control @error('end_date') border-danger is-invalid @enderror">
                        @error('end_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="card-body">
                {{-- Debtors Report --}}
                @if (count($debtors) > 0)
                    <div class="">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold text-danger mb-0">Debtors Report</h3>
                            <button onclick="printReceipt2('printableArea1')" class="btn btn-outline-success">
                                <i class="bi bi-printer"></i> Print Now
                            </button>
                        </div>
                        <div id="printableArea1">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered custom-report-table">
                                    <thead class="table-danger">
                                        <tr>
                                            <th style="text-align: center;text-transform:uppercase;" colspan="5">
                                                Debtors Report</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th>Customer</th>
                                            <th>Phone</th>
                                            <th>Order Number</th>
                                            <th>Balance({{ $currency }})</th>
                                            <th>Due Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($debtors as $debtor)
                                            <tr class="align-middle text-center">
                                                <td>{{ $debtor->customer->name }}</td>
                                                <td>{{ $debtor->customer->phone }}</td>
                                                <td>{{ $debtor->order_number }}</td>
                                                <td><span
                                                        class="badge bg-danger fs-6">{{ $currency }}{{ number_format($debtor->balance, 2) }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($debtor->due_date)->format('d M Y') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="alert alert-warning text-center">No debtors found.</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                @endif

                {{-- Sales Today Report --}}
                @if (count($salesToday) > 0)
                    <div class="">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold text-primary mb-0">Sales Today</h3>
                            <button onclick="printReceipt2('printableArea2')" class="btn btn-outline-success">
                                <i class="bi bi-printer"></i> Print Now
                            </button>
                        </div>

                        <div id="printableArea2">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered custom-report-table">
                                    <thead class="table-primary">
                                        <tr>
                                            <th style="text-align: center;text-transform:uppercase;" colspan="5">
                                                Sales
                                                Today</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th>Customer</th>
                                            <th>Order Number</th>
                                            <th>Total({{ $currency }})</th>
                                            <th>Paid({{ $currency }})</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($salesToday as $sale)
                                            <tr class="align-middle text-center">
                                                <td>{{ $sale->customer->name }}</td>
                                                <td>{{ $sale->order_number }}</td>
                                                <td><span
                                                        class="badge bg-warning fs-6">{{ $currency }}{{ number_format($sale->amount_payable, 2) }}</span>
                                                </td>
                                                <td><span
                                                        class="badge bg-success fs-6">{{ $currency }}{{ number_format($sale->amount_paid, 2) }}</span>
                                                </td>
                                                <td>{{ $sale->created_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="alert alert-warning text-center">No sales found for
                                                        today.</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Top Selling Products --}}
                @if (count($topSellingProducts) > 0)
                    <div class="">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold text-success mb-0">Top Selling Products</h3>
                            <button onclick="printReceipt2('printableArea3')" class="btn btn-outline-success">
                                <i class="bi bi-printer"></i> Print Now
                            </button>
                        </div>

                        <div id="printableArea3">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered custom-report-table">
                                    <thead class="table-success">
                                        <tr>
                                            <th style="text-align: center;text-transform:uppercase;" colspan="2">
                                                Top
                                                Selling Products</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th>Product Name</th>
                                            <th>Sold Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($topSellingProducts as $product)
                                            <tr class="align-middle text-center">
                                                <td>{{ $product->name }}</td>
                                                <td><span
                                                        class="badge bg-primary fs-6">{{ $product->order_details_count }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">
                                                    <div class="alert alert-warning text-center">No top selling
                                                        products
                                                        found.</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Sales Summary --}}
                @if (!empty($salesSummary))
                    <div class="">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold text-warning mb-0">Sales Summary Report</h3>
                            <button onclick="printReceipt2('printableArea4')" class="btn btn-outline-success">
                                <i class="bi bi-printer"></i> Print Now
                            </button>
                        </div>
                        <div id="printableArea4">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-center">
                                    <thead class="table-success">
                                        <tr>
                                            <th style="text-align: center;text-transform:uppercase;" colspan="3">
                                                Sales Summary
                                                Report</th>
                                        </tr>
                                        <tr>
                                            <th>Summary</th>
                                            <th colspan="2">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Total Paid Sales</strong></td>
                                            <td class="text-success" colspan="2">
                                                {{ $currency }}{{ number_format($salesSummary['total_paid_sales'], 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Payable Sales</strong></td>
                                            <td class="text-primary" colspan="2">
                                                {{ $currency }}{{ number_format($salesSummary['total_payable_sales'], 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Outstanding Sales</strong></td>
                                            <td class="text-danger" colspan="2">
                                                {{ $currency }}{{ number_format($salesSummary['total_outstanding_sales'], 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Orders</strong></td>
                                            <td class="text-info" colspan="2">{{ $salesSummary['total_orders'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" style="text-align: center;text-transform:uppercase;">
                                                Revenue Calculations
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Total Sales Amount:</td>
                                            <td>{{ $currency }}{{ number_format($salesSummary['total_paid_sales'], 2) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Total Expenditures:</td>
                                            <td>{{ $currency }}{{ number_format($salesSummary['total_expenditures'], 2) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Net Revenue (
                                                @if ($salesSummary['net_revenue'] < 0)
                                                    Loss
                                                @else
                                                    Profit
                                                @endif
                                                ):
                                            </td>
                                            <td>
                                                @if ($salesSummary['net_revenue'] < 0)
                                                    <span
                                                        class="text-danger">{{ $currency }}{{ number_format($salesSummary['net_revenue'], 2) }}</span>
                                                @else
                                                    <span
                                                        class="text-success">{{ $currency }}{{ number_format($salesSummary['net_revenue'], 2) }}</span>
                                                @endif
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Low Stock Products --}}
                @if (count($lowStockProducts) > 0)
                    <div class="">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold text-danger mb-0">Low Stock Products</h3>
                            <button onclick="printReceipt2('printableArea5')" class="btn btn-outline-success">
                                <i class="bi bi-printer"></i> Print Now
                            </button>
                        </div>
                        <div id="printableArea5">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered custom-report-table">
                                    <thead class="table-danger">
                                        <tr>
                                            <th style="text-align: center;text-transform:uppercase;" colspan="2">
                                                Low
                                                Stock Products</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th>Product Name</th>
                                            <th>Stock Left</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($lowStockProducts as $product)
                                            <tr class="align-middle text-center">
                                                <td>{{ $product->name }}</td>
                                                <td><span
                                                        class="badge bg-warning text-dark fs-6">{{ $product->stock }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">
                                                    <div class="alert alert-warning text-center">No low stock
                                                        products found.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
