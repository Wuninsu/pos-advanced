<?php

namespace App\Livewire\Admin;

use App\Models\Expenditure;
use App\Models\OrdersModel;
use App\Models\ProductsModel;
use App\Models\SettingsModel;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Carbon\Carbon;

class Reports extends Component
{
    public $debtors = [];
    public $salesToday = [];
    public $topSellingProducts = [];
    public $salesSummary = [];
    public $lowStockProducts = [];

    #[Validate('nullable|date|before_or_equal:end_date')]
    public $start_date;

    #[Validate('nullable|date|after_or_equal:start_date')]
    public $end_date;

    public $currency;

    public function mount()
    {
        $settings = SettingsModel::getSettingsData();
        $this->currency = $settings['currency'] ?? 'GHS';
        $this->end_date = Carbon::now()->format('Y-m-d');
        $this->start_date = Carbon::now()->subDays(30)->format('Y-m-d');
    }

    public function loadDebtorsReport()
    {
        $this->resetReports();

        $query = OrdersModel::with('customer')
            ->where('payment_method', 'credit');

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('due_date', [
                Carbon::parse($this->start_date)->startOfDay(),
                Carbon::parse($this->end_date)->endOfDay()
            ]);
        }

        $this->debtors = $query->orderBy('due_date', 'asc')->get();
    }

    public function loadSalesTodayReport()
    {
        $this->resetReports();

        $query = OrdersModel::with('customer')
            ->where('payment_method', 'cash');

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->start_date)->startOfDay(),
                Carbon::parse($this->end_date)->endOfDay()
            ]);
        } else {
            $query->whereDate('created_at', today());
        }

        $this->salesToday = $query->get();
    }

    public function loadTopSellingProductsReport()
    {
        $this->resetReports();

        $query = ProductsModel::withCount(['orderDetails' => function ($q) {
            if ($this->start_date && $this->end_date) {
                $q->whereBetween('created_at', [
                    Carbon::parse($this->start_date)->startOfDay(),
                    Carbon::parse($this->end_date)->endOfDay()
                ]);
            }
        }]);

        $this->topSellingProducts = $query
            ->orderByDesc('order_details_count')
            ->take(10)
            ->get();
    }

    // public function loadSalesSummaryReport()
    // {
    //     $this->resetReports();

    //     $query = OrdersModel::query()
    //         ->where('payment_method', 'cash'); // Or include credit if you want full

    //     if ($this->start_date && $this->end_date) {
    //         $query->whereBetween('created_at', [
    //             Carbon::parse($this->start_date)->startOfDay(),
    //             Carbon::parse($this->end_date)->endOfDay()
    //         ]);
    //     }

    //     $this->salesSummary = [
    //         'total_paid_sales' => $query->sum('amount_paid'),
    //         'total_payable_sales' => $query->sum('amount_payable'),
    //         'total_outstanding_sales' => $query->sum('balance'),
    //         'total_orders' => $query->count(),
    //     ];
    // }


    public function loadSalesSummaryReport()
    {
        $this->resetReports();

        // Query for sales data
        $query = OrdersModel::query()
            ->where('payment_method', 'cash'); // Or include credit if you want full

        // Filter by date range if provided
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->start_date)->startOfDay(),
                Carbon::parse($this->end_date)->endOfDay()
            ]);
        }

        // Sales summary
        $totalPaidSales = $query->sum('amount_paid');
        $totalExpenditures = Expenditure::query()
            ->whereBetween('created_at', [
                Carbon::parse($this->start_date)->startOfDay(),
                Carbon::parse($this->end_date)->endOfDay()
            ])
            ->sum('amount');

        // Calculate net revenue (profit)
        $netRevenue = $totalPaidSales - $totalExpenditures;

        // Store the sales summary
        $this->salesSummary = [
            'total_paid_sales' => $totalPaidSales,
            'total_payable_sales' => $query->sum('amount_payable'),
            'total_outstanding_sales' => $query->sum('balance'),
            'total_orders' => $query->count(),
            'total_expenditures' => $totalExpenditures,
            'net_revenue' => $netRevenue,
        ];
    }


    public function loadLowStockProductsReport()
    {
        $this->resetReports();

        $this->lowStockProducts = ProductsModel::where('stock', '>', 0)
            ->where('stock', '<', lowStock())
            ->orderBy('stock', 'asc')
            ->get();
    }

    private function resetReports()
    {
        $this->debtors = [];
        $this->salesToday = [];
        $this->topSellingProducts = [];
        $this->salesSummary = [];
        $this->lowStockProducts = [];
    }

    #[Title('Reports')]
    public function render()
    {
        return view('livewire.admin.reports');
    }
}
