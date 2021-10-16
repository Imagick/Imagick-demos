<?php

namespace ImagickDemo\Tutorial;

class svgExample extends \ImagickDemo\Example
{

    public function renderTitle(): string
    {
        return "SVG example";
    }

    public function render(
        ?string $activeCategory,
        ?string $activeExample
    )
    {
        $output = "";
        $output .= $this->renderImageURL();
        return $output;
    }
}
