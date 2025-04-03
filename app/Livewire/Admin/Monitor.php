<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Title;
use Livewire\Component;

class Monitor extends Component
{
    #[Title('Monitor')]
    public function render()
    {
        return view('livewire.admin.monitor')->layout('layouts.guest');
    }
}
