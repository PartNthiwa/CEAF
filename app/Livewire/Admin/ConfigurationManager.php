<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Configuration;

class ConfigurationManager extends Component
{

    public $year;
    public $configs = [];

    public function mount()
    {
        $this->year = now()->year;

        $this->configs = Configuration::where('year', $this->year)
            ->pluck('value', 'key')
            ->toArray();
    }

    public function save()
    {
        foreach ($this->configs as $key => $value) {
            Configuration::updateOrCreate(
                ['year' => $this->year, 'key' => $key],
                ['value' => $value]
            );
        }

        session()->flash('success', 'Configuration updated successfully.');
    }

    public function render()
    {
        $configurations = Configuration::all();
        return view('livewire.admin.configuration-manager', ['configurations' => $configurations])  
        ->layout('layouts.admin');
    }
}
