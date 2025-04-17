<?php

namespace App\Livewire;

use App\Models\SettingsModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Settings extends Component
{
    use WithFileUploads;
    public $settings = [];

    public function mount()
    {
        // Load the settings as an associative array
        $settingsData = SettingsModel::all();
        foreach ($settingsData as $setting) {
            $this->settings[$setting->key] = $setting->value;
        }
    }

    public function updateData()
    {
        foreach ($this->settings as $key => $value) {
            // Find the setting by 'key' instead of 'id'
            $setting = SettingsModel::where('key', $key)->first();

            if ($setting) {
                // Handle file uploads for logo and favicon
                if (($key == 'logo' || $key == 'favicon') && $this->settings[$key] instanceof TemporaryUploadedFile) {
                    $newFileName = $key . '_' . time() . '.' . $this->settings[$key]->extension();
                    $filePath = uploadFile($this->settings[$key], 'uploads', $newFileName);
                    $value = $filePath; // Set value to file path
                }

                // Update the settings table using 'key'
                SettingsModel::where('key', $key)->update(['value' => $value]);
            }
        }


        toastr()->success('Settings updated successfully!');
    }


    #[Title('Configuration')]
    public function render()
    {
        return view('livewire.settings');
    }
}
