<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PreferencesSettings extends Component
{
    public array $preferences = [];

    public function mount()
    {
        $this->loadPreferences();
    }

    public function loadPreferences()
    {
        $this->preferences = DB::table('preferences')->pluck('value', 'key')->map(function ($val) {
            return (bool) $val;
        })->toArray();
    }

    public function toggle($key)
    {
        $newValue = !$this->preferences[$key];

        DB::table('preferences')->where('key', $key)->update(['value' => $newValue]);
        $this->preferences[$key] = $newValue;

        toastr(ucfirst(str_replace('_', ' ', $key)) . ' updated!', 'success');
        // session()->flash('message', ucfirst(str_replace('_', ' ', $key)) . ' updated!');
    }

    public function render()
    {
        return view('livewire.preferences-settings');
    }
}
