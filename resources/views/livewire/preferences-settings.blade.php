<div>
    <div class="card p-4">
        <h4 class="mb-4">System Preferences</h4>

        @if (session()->has('message'))
            <div class="alert alert-success text-sm">{{ session('message') }}</div>
        @endif

        @foreach ($preferences as $key => $value)
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" role="switch" id="{{ $key }}"
                    wire:click="toggle('{{ $key }}')" @checked($value)>
                <label class="form-check-label" for="{{ $key }}">
                    {{ ucwords(str_replace('_', ' ', $key)) }}
                </label>
            </div>
        @endforeach
    </div>

</div>
