<button onclick="window.print()" class="btn btn-primary">Print Debtors Report</button>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Phone</th>
            <th>Order #</th>
            <th>Balance</th>
            <th>Due Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($debtors as $debtor)
            <tr>
                <td>{{ $debtor->customer->name }}</td>
                <td>{{ $debtor->customer->phone }}</td>
                <td>{{ $debtor->order_number }}</td>
                <td>{{ number_format($debtor->balance, 2) }}</td>
                <td>{{ $debtor->due_date->format('d M Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
