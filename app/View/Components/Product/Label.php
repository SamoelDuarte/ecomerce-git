<?php

namespace App\View\Components\Product;

use Illuminate\View\Component;

class Label extends Component
{
    public $label;
    public $color;

    public function __construct($label, $color)
    {
        $this->label = $label;
        $this->color = $color;
    }

    public function render()
    {
        return view('components.product.label');
    }
}
