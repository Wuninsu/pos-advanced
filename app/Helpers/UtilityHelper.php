<?php

use App\Models\OrdersModel;
use App\Models\ProductsModel;
use App\Models\settingsModel;
use App\Models\SettingsModel as ModelsSettingsModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


if (!function_exists('uploadFile')) {
    /**
     * Upload and rename a file dynamically.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param string|null $customName
     * @return string The path of the uploaded file.
     */
    function uploadFile($file, $directory = 'uploads', $customName = null)
    {
        // Ensure directory exists
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        // Generate a unique name if a custom name isn't provided
        $extension = $file->getClientOriginalExtension();
        $filename = $customName
            ? Str::slug($customName) . '.' . $extension
            : Str::uuid()->toString() . '.' . $extension;

        // Upload the file
        $path = $file->storeAs($directory, $filename);
        return $path;
    }
}

if (!function_exists('can_cashier_delete_data')) {
    function can_cashier_delete_data(): bool
    {
        $user = Auth::user();

        if ($user && $user->role === 'cashier') {
            $isAllowed = DB::table('preferences')->where('key', 'allow_cashier_delete_data')->value('value');

            if (!$isAllowed) {
                toastr('You are not allowed to delete data.', 'warning');
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('paginationLimit')) {
    function paginationLimit()
    {
        $settings = ModelsSettingsModel::getSettingsData();
        return $settings['pagination_limit'] ?? 7;
    }
}

if (!function_exists('lowStock')) {
    function lowStock()
    {
        $settings = ModelsSettingsModel::getSettingsData();
        return $settings['low_stock'] ?? 100;
    }
}


if (!function_exists('intWithStyle')) {
    /** Style the number value with a suffix like 10M or 10K or 10.3B */
    function intWithStyle($n)
    {
        return $n;
        if ($n < 1000) return $n;
        $suffix = ['', 'K', 'M', 'B', 'T', 'P', 'E', 'Z', 'Y'];
        $power = floor(log($n, 1000));
        return round($n / (1000 ** $power), 1, PHP_ROUND_HALF_EVEN) . $suffix[$power];
    }
}

if (!function_exists('generateTransactionNumber')) {
    function generateTransactionNumber($prefix = 'TXN')
    {
        do {
            $timestamp = time(); // Current timestamp
            $randomString = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6)); // 6-character random string
            $transactionNumber = $prefix . '-' . $timestamp . '-' . $randomString;

            // Check if transaction number already exists in the database
            $exists = DB::table('transactions')->where('transaction_number', $transactionNumber)->exists();
        } while ($exists); // Keep generating until a unique one is found

        return $transactionNumber;
    }
}

if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber(string $column = 'order_number', string $prefix = 'ORD-', int $length = 5)
    {
        $data = DB::table('orders')
            ->whereNotNull($column)
            ->orderBy($column, 'desc')
            ->first();

        if (!$data) {
            $last_number = "1";
            $og_length = $length - strlen($last_number);
        } else {
            $code = preg_replace('/[^0-9]/', '', $data->$column);
            $increment_last_number = ((int)$code) + 1;
            $last_number = (string)$increment_last_number;
            $og_length = $length - strlen($last_number);
        }

        // Ensure at least 0 is passed to str_repeat
        $zeros = str_repeat("0", max(0, $og_length));

        return $prefix . $zeros . $last_number;
    }
}


if (!function_exists('generateInvoiceNumber')) {
    function generateInvoiceNumber(string $column = 'invoice_number', string $prefix = 'INV-', int $length = 5)
    {
        $data = DB::table('invoices')
            ->whereNotNull($column)
            ->orderBy($column, 'desc')
            ->first();

        if (!$data) {
            $last_number = "1";
            $og_length = $length - strlen($last_number);
        } else {
            $code = preg_replace('/[^0-9]/', '', $data->$column);
            $increment_last_number = ((int)$code) + 1;
            $last_number = (string)$increment_last_number;
            $og_length = $length - strlen($last_number);
        }

        // Ensure at least 0 is passed to str_repeat
        $zeros = str_repeat("0", max(0, $og_length));

        return $prefix . $zeros . $last_number;
    }
}


if (!function_exists('companyData')) {
    function companyData()
    {
        return SettingsModel::getSettingsData();
    }
}


if (!function_exists('allTimePayments')) {
    function allTimePayments()
    {
        $results = OrdersModel::select('transactions')
            ->where('status', 'completed')
            ->sum('order_amount');
        return intWithStyle(number_format($results, 2, '.', ''));
    }
}
if (!function_exists('ordersThisYear')) {
    function ordersThisYear()
    {
        // Get the start and end dates for the current year
        $startOfYear = Carbon::now()->startOfYear()->toDateTimeString();
        $endOfYear = Carbon::now()->endOfYear()->toDateTimeString();

        // Query payments made within the current year
        $results = OrdersModel::whereBetween('created_at', [$startOfYear, $endOfYear])
            ->where('status', 'completed')
            ->sum('order_amount');

        return intWithStyle($results);
    }
}
if (!function_exists('ordersToday')) {

    function ordersToday()
    {
        $date = Carbon::now()->subDays()->toDateTimeString();
        $results = OrdersModel::select('transactions')
            ->where('status', 'completed')
            ->whereDate('created_at', '>=', $date)
            ->sum('order_amount');
        return intWithStyle($results);
    }
}
if (!function_exists('ordersThisMonth')) {
    function ordersThisMonth()
    {
        $date = Carbon::now()->subDays(30);
        $results = OrdersModel::select('transactions')
            ->where('status', 'completed')
            ->whereDate('created_at', '>=', $date)
            ->sum('order_amount');
        return intWithStyle($results);
    }
}
if (!function_exists('ordersThisWeek')) {
    function ordersThisWeek()
    {
        $date = Carbon::now()->subDays(7);
        $results = OrdersModel::select('transactions')
            ->where('status', 'completed')
            ->whereDate('created_at', '>=', $date)
            ->sum('order_amount');
        return intWithStyle($results);
    }
}

// order count
if (!function_exists('allTimeCount')) {
    function allTimeCount()
    {
        $results = OrdersModel::select('transactions')->count('id');
        return intWithStyle($results);
    }
}
if (!function_exists('ordersCountThisYear')) {
    function ordersCountThisYear()
    {
        // Get the start and end dates for the current year
        $startOfYear = Carbon::now()->startOfYear()->toDateTimeString();
        $endOfYear = Carbon::now()->endOfYear()->toDateTimeString();

        // Query payments made within the current year
        $results = OrdersModel::whereBetween('created_at', [$startOfYear, $endOfYear])
            ->where('status', 'completed')
            ->count('id');

        return intWithStyle($results);
    }
}
if (!function_exists('ordersCountToday')) {

    function ordersCountToday()
    {
        $date = Carbon::now()->subDays()->toDateTimeString();
        $results = OrdersModel::select('transactions')
            ->whereDate('created_at', '>=', $date)
            ->where('status', 'completed')
            ->count('id');
        return intWithStyle($results);
    }
}
if (!function_exists('ordersCountThisMonth')) {
    function ordersCountThisMonth()
    {
        $date = Carbon::now()->subDays(30);
        $results = OrdersModel::select('transactions')
            ->whereDate('created_at', '>=', $date)
            ->where('status', 'completed')
            ->count('id');
        return intWithStyle($results);
    }
}
if (!function_exists('ordersCountThisWeek')) {
    function ordersCountThisWeek()
    {
        $date = Carbon::now()->subDays(7);
        $results = OrdersModel::select('transactions')
            ->whereDate('created_at', '>=', $date)
            ->where('status', 'completed')
            ->count('id');
        return intWithStyle($results);
    }
}


if (!function_exists('getOrdersChartData')) {
    function getOrdersChartData()
    {
        $totalOrders = OrdersModel::count();

        $lowStock = lowStock();
        $pending = OrdersModel::where('status', 'pending')->count();
        $canceled = OrdersModel::where('status', 'canceled')->count();
        $completed = OrdersModel::where('status', 'completed')->count();

        // Avoid division by zero
        $pendingAmount = OrdersModel::where('status', 'pending')->sum('order_amount');
        $canceledAmount = OrdersModel::where('status', 'canceled')->sum('order_amount');
        $completedAmount = OrdersModel::where('status', 'completed')->sum('order_amount');

        $percentPending = $totalOrders ? ($pending / $totalOrders) * 100 : 0;
        $percentCanceled = $totalOrders ? ($canceled / $totalOrders) * 100 : 0;
        $percentCompleted = $totalOrders ? ($completed / $totalOrders) * 100 : 0;



        return [
            'pending' => [
                'count' => $pending,
                'amount' => intWithStyle($pendingAmount),
                'percentage' => $percentPending,
            ],
            'canceled' => [
                'count' => $canceled,
                'amount' => intWithStyle($canceledAmount),
                'percentage' => $percentCompleted,
            ],
            'completed' => [
                'count' => $completed,
                'amount' => intWithStyle($completedAmount),
                'percentage' => $percentPending,
            ],
        ];
    }
}


if (!function_exists('sendSMS')) {
    /**
     * Summary of sendSMS
     * @param array $data
     * @return bool
     */
    function sendSMS($data): bool
    {
        // Define parameters
        $api_key = "THNhUHBkSmtqdFlIYnlCVE52ZHg";
        $from = "EchoEdgePOS";
        $to = $data['phone']; // Recipient's phone number
        $msg = urlencode($data['message']); // Encode the message

        // Initialize cURL request
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sms.arkesel.com/sms/api?action=send-sms&api_key=$api_key&to=$to&from=$from&sms=$msg",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        // Execute cURL request
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            // Handle cURL error
            $error_msg = curl_error($curl);
            curl_close($curl);
            // Log or handle the error message
            return false;
        }
        curl_close($curl);

        // Handle the API response
        if ($response) {
            $result = trim($response, '[]');
            $sms_res = json_decode($result);

            if ($sms_res && isset($sms_res->code) && $sms_res->code == 'ok') {
                return true; // SMS sent successfully
            }
        }

        // Default failure case
        return false;
    }
}

if (!function_exists('getLowStockProducts')) {
    function getLowStockProducts($threshold = 100)
    {
        $settings = ModelsSettingsModel::getSettingsData();
        $lowStock = $settings['low_stock'] ?? 100;
        return $products = ProductsModel::where('stock', '>', 0)
            ->where('stock', '<=', $lowStock)
            ->get();
    }
}

if (!function_exists(' getOutOfStockProducts')) {
    function getOutOfStockProducts()
    {
        return ProductsModel::where('stock', '=', 0)->get();
    }
}
