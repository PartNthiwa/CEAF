<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Stat extends Component
{
    public string $title;
    public string|int $value;
    public string $color;

    public function __construct(
        string $title,
        string|int $value,
        string $color = 'gray'
    ) {
        $this->title = $title;
        $this->value = $value;
        $this->color = $color;
    }

    public function render()
    {
        return view('components.admin.stat');
    }
}
