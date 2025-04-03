<div>
    <form wire:submit.prevent="updateData">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @if ($settings)
                        @foreach ($settings as $key => $value)
                            <div class="col-md-6 form-group mb-2">
                                <label for="value_{{ $key }}"
                                    class="mb-0">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>

                                @if ($key == 'logo' || $key == 'favicon')
                                    <input type="file" id="value_{{ $key }}"
                                        wire:model="settings.{{ $key }}"
                                        class="form-control @error('settings.' . $key) border-danger is-invalid @enderror">

                                    @if ($value)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . ($value ?? NO_IMAGE)) }}"
                                                alt="{{ $key }}" width="100">
                                        </div>
                                    @endif
                                @else
                                    <input type="text" id="value_{{ $key }}"
                                        wire:model="settings.{{ $key }}"
                                        class="form-control @error('settings.' . $key) border-danger is-invalid @enderror">
                                @endif

                                @error('settings.' . $key)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endforeach
                    @else
                        <div class="form-group">
                            <span class="text-danger">No records found</span>
                        </div>
                    @endif

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary float-end">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
