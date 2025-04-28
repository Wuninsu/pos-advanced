<div>
    <div class="row">

        @php
            $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
        @endphp
        <style>
            .highlight {
                background-color: #e9f7ef;
                border-left: 4px solid #198754;
            }

            .status {
                background-color: #fff3cd;
                border-left: 4px solid #ffc107;
            }

            .revenue {
                background-color: #e2f0fb;
                border-left: 4px solid #0d6efd;
                font-weight: bold;
            }
        </style>
        <div class="card">
            <div class="card-body">
                <div class="list-group g-3 mt-2">
                    <div class="row">
                        <div class="col-md-6">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Client:</strong> <span>{{ $serviceRequest->client }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Service:</strong> <span>{{ $serviceRequest->service->name ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Loading Place:</strong> <span>{{ $serviceRequest->loading_place }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Destination:</strong> <span>{{ $serviceRequest->destination }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Quantity:</strong> <span>{{ $serviceRequest->quantity }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Unit Price:</strong>
                                <span>{{ $settings['currency'] ?? 'GHS' }}{{ number_format($serviceRequest->unit_price, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Unit of Measurement:</strong>
                                <span>{{ $serviceRequest->unit->name ?? '' }}({{ $serviceRequest->unit->abbreviation ?? '' }})</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between highlight">
                                <strong>Amount:</strong>
                                <span>{{ $settings['currency'] ?? 'GHS' }}{{ number_format($serviceRequest->amount, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Remarks:</strong>
                                <span>{{ $serviceRequest->remarks ?? 'No remarks' }}</span>
                            </li>
                        </div>

                        {{-- Percentages --}}
                        <div class="col-md-6">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Fuel ({{ $serviceRequest->fuel }}%):</strong>
                                <span>{{ $settings['currency'] ?? 'GHS' }}{{ number_format(($serviceRequest->amount * (float) $serviceRequest->fuel) / 100, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Allowance ({{ $serviceRequest->allowance }}%):</strong>
                                <span>{{ $settings['currency'] ?? 'GHS' }}{{ number_format(($serviceRequest->amount * (float) $serviceRequest->allowance) / 100, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Feeding ({{ $serviceRequest->feeding }}%):</strong>
                                <span>{{ $settings['currency'] ?? 'GHS' }}{{ number_format(($serviceRequest->amount * (float) $serviceRequest->feeding) / 100, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Maintenance ({{ $serviceRequest->maintenance }}%):</strong>
                                <span>{{ $settings['currency'] ?? 'GHS' }}{{ number_format(($serviceRequest->amount * (float) $serviceRequest->maintenance) / 100, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Owner ({{ $serviceRequest->owner }}%):</strong>
                                <span>{{ $settings['currency'] ?? 'GHS' }}{{ number_format(($serviceRequest->amount * (float) $serviceRequest->owner) / 100, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Other Expenses ({{ $serviceRequest->other_expenses }}%):</strong>
                                <span>{{ $settings['currency'] ?? 'GHS' }}{{ number_format(($serviceRequest->amount * (float) $serviceRequest->other_expenses) / 100, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between revenue">
                                <strong>Revenue:</strong>
                                <span>{{ $settings['currency'] ?? 'GHS' }}{{ number_format($serviceRequest->revenue, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between status">
                                <strong>Status:</strong> <span>{{ ucfirst($serviceRequest->status) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Date:</strong>
                                <span>{{ \Carbon\Carbon::parse($serviceRequest->date)->format('d M, Y') }}</span>
                            </li>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>
