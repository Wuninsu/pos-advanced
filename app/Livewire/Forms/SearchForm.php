<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Url;
use Livewire\Component;

class SearchForm extends Component
{
    #[Url]
    public $search;

    public function render()
    {
        $results = [];

        if (strlen($this->search) > 2) {
            $results = auth('web')->user()->suppliers()->where('company_name', 'like', '%' . $this->search . '%')->get();
        }
        return view('livewire.forms.search-form', [
            'results' => $results,
        ]);
    }
}
