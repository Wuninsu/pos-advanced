<?php

namespace App\Livewire\Admin;

use App\Events\NotificationAlert;
use App\Models\CategoriesModel;
use App\Models\CustomersModel;
use App\Models\OrdersModel;
use App\Models\ProductsModel;
use App\Models\SettingsModel;
use App\Models\SuppliersModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount() {}

    public function getProductStockInfo()
    {
        $totalProducts = ProductsModel::count();

        $lowStock = lowStock();
        $lowStockCount = ProductsModel::where('stock', '>', 0)->where('stock', '<=', $lowStock)->count();
        $goodStockCount = ProductsModel::where('stock', '>', $lowStock)->count();
        $outOfStockCount = ProductsModel::where('stock', '<', 1)->count();

        // Avoid division by zero
        $percentLowStock = $totalProducts ? ($lowStockCount / $totalProducts) * 100 : 0;
        $percentGoodStock = $totalProducts ? ($goodStockCount / $totalProducts) * 100 : 0;
        $percentOutOfStock = $totalProducts ? ($outOfStockCount / $totalProducts) * 100 : 0;

        return [
            'low_stock' => ['count' => $lowStockCount, 'percentage' => round($percentLowStock, 2)],
            'high_stock' => ['count' => $goodStockCount, 'percentage' => round($percentGoodStock, 2)],
            'out_of_stock' => ['count' => $outOfStockCount, 'percentage' => round($percentOutOfStock, 2)],
        ];
    }


    public function getDashboardStats()
    {
        $settings = SettingsModel::getSettingsData();
        $lowStock = $settings['low_stock'];
        return [
            'total_users' => User::count(),
            'total_customers' => CustomersModel::count(),
            'total_categories' => CategoriesModel::count(),
            'total_products' => ProductsModel::count(),
            'total_suppliers' => SuppliersModel::count(),
            'out_of_stock' => ProductsModel::where('stock', '<=', 0)->count(),
            'low_stock' => ProductsModel::where('stock', '<=', $lowStock)->where('stock', '>', 0)->count(),
            'pending_orders' => OrdersModel::where('status', 'pending')->count(),
            'completed_orders' => OrdersModel::where('status', 'completed')->count(),
            'canceled_orders' => OrdersModel::where('status', 'canceled')->count(),

        ];
    }


    public function getTopSellingProducts()
    {
        $topProducts = DB::table('order_details')
            ->select('products.name', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->groupBy('order_details.product_id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        return $topProducts;
    }


    public function getTopProductsSuppliers()
    {
        $totalProducts = DB::table('products')->count();

        if ($totalProducts === 0) {
            return collect();
        }

        $topProducts = DB::table('products')
            ->select(
                'suppliers.company_name',
                DB::raw('COUNT(products.id) as total_products')
            )
            ->join('suppliers', 'suppliers.id', '=', 'products.supplier_id')
            ->groupBy('products.supplier_id', 'suppliers.company_name')
            ->orderByDesc('total_products')
            ->limit(10)
            ->get();

        // Add percentage in PHP
        return $topProducts->map(function ($item) use ($totalProducts) {
            $item->percentage = round(($item->total_products / $totalProducts) * 100, 2);
            return $item;
        });
    }



    #[Title('Dashboard')]
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'productsStock' => $this->getProductStockInfo(),
            'dashboardStats' => $this->getDashboardStats(),
            'topProducts' => $this->getTopSellingProducts(),
            'topSuppliers' => $this->getTopProductsSuppliers(),
        ]);
    }
}
