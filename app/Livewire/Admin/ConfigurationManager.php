<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Configuration;

class ConfigurationManager extends Component
{
    public $year;
    public $amount_per_event;
    public $number_of_events;

    protected $rules = [
        'year' => 'required|integer|min:2000',
        'amount_per_event' => 'required|numeric|min:1',
        'number_of_events' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->year = now()->year;

        $config = Configuration::firstWhere('year', $this->year);

        if($config) {
            $this->amount_per_event = $config->amount_per_event;
            $this->number_of_events = $config->number_of_events;
        }
    }

    public function save()
    {
        $this->validate();

        Configuration::updateOrCreate(
            ['year' => $this->year],
            [
                'amount_per_event' => $this->amount_per_event,
                'number_of_events' => $this->number_of_events
            ]
        );

        session()->flash('success', "Configuration for {$this->year} saved successfully.");
    }

    public function render()
    {
        return view('livewire.admin.configuration-manager')->layout('layouts.admin');
    }
}