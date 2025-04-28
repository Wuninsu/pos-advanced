<?php

namespace App\Livewire\Admin;

use App\Models\SmsTemplate;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class SmsTemplates extends Component
{
    use WithPagination;
    public $search = ''; // Search term
    public $template_id;
    public $confirmingDelete = false; // To show a confirmation dialog
    public $isEdit = false; // To show a confirmation dialog
    public $name, $template;
    public $sanitizedSearch;

    public function mount() {}

    protected $queryString = [
        'search',
    ];

    protected function rules()
    {
        return [
            'name' => 'required|min:4|max:255|unique:sms_templates,name,' . $this->template_id,
            'template' => 'required|string',
        ];
    }

    public function saveTemplate()
    {
        $validatedData = $this->validate(); // validate user inputs 
        $save = SmsTemplate::create($validatedData); // save data
        if (!$save) {
            flash()->error('Fail to create template. Please try again.'); // send error msg
        }
        flash()->success('Template created successfully.'); // send success msg
        $this->reset(); // reset form fields

    }


    public function editTemplate(SmsTemplate $template)
    {
        $this->template_id = $template->id;
        $this->name = $template->name;
        $this->template = $template->template;
        $this->isEdit = true;
    }

    public function updateTemplate()
    {
        $validatedData = $this->validate();
        $template = SmsTemplate::find($this->template_id);
        if ($template) {
            $template->update($validatedData);
            $this->reset(); // Clear the form fields
            flash()->success('Template updated successfully.');
        } else {
            flash()->error('Template not found.');
        }
    }

    public $templateId;
    public $showDelete = false;
    public function confirmDelete($id)
    {
        $this->templateId = $id;
        $this->showDelete = true;
    }

    #[On('delete')]
    public function handleDelete()
    {
        $template = SmsTemplate::find($this->templateId);

        if ($template) {
            $template->delete();
            flash()->success('Template deleted successfully.');
        } else {
            flash()->error('Template not found.');
        }
        $this->reset();
    }


    #[Title('Sms Templates')]
    public function render()
    {
        // Fetch template based on the search term or paginate all template
        $this->sanitizedSearch = str_replace(["'", '"'], '', $this->search);
        $templates = SmsTemplate::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(7);
        return view('livewire.admin.sms-templates', ['templates' => $templates]);
    }
}
