<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <h5>SMS Logs</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td colspan="2">
                                    <div class="input-group">
                                        @if (!empty($reTryStatus))
                                            <span class="bg-warning text-dark p-2">Retried:
                                                {{ $reTryStatus['retried'] ?? 0 }}</span>
                                            <span class="bg-success text-white p-2">Success:
                                                {{ $reTryStatus['success'] ?? 0 }}</span>
                                            <span class="bg-danger text-white p-2">Failed: {{ $reTryStatus['failed'] ?? 0 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td colspan="2" align="right">
                                    <div class="input-group float-end">
                                        <button type="button" wire:click='retryFailedSms()' class="btn btn-dark"
                                            wire:loading.attr="disabled">
                                            <span><i class="fa fa-redo"></i> Resend All Failed
                                                SMS</span>
                                        </button>
                                        <button type="button" wire:click='deleteAllSent()' class="btn btn-warning"
                                            wire:loading.attr="disabled">
                                            <span><i class="fa fa-check"></i> Delete All Sent
                                                SMS</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="table-secondary">
                                <th>#</th>
                                <th>Recipient</th>
                                <th>Sms Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($savedSmsLogs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->recipient_phone }}</td>
                                    <td>{{ $log->message_type }}</td>
                                    <td>
                                        @if ($log->status == 'sent')
                                            {{ $log->status }} <i class="fa fa-check-circle text-success"></i>
                                        @elseif ($log->status == 'failed')
                                            {{ $log->status }} <i class="fa fa-times-circle text-danger"></i>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="border-danger text-center mx-4 my-auto">
                                            <i class="fa fa-exclamation-circle fa-2x text-danger fs-3"></i>
                                            <h5 class="card-title text-danger mt-2">No SMS Logs Found</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if (!empty($savedSmsLogs))
                        <div>{{ $savedSmsLogs->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
