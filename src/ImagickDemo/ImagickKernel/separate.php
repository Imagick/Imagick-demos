<?php

namespace ImagickDemo\ImagickKernel;

use ImagickDemo\Display;

class separate extends \ImagickDemo\Example
{
    public function renderTitle(): string
    {
        return "separate";
    }


    public function renderDescription()
    {
        return "Separates a linked set of kernels and returns an array of ImagickKernels.";
    }

    public function render(
        ?string $activeCategory,
        ?string $activeExample
    )
    {
//Example ImagickKernel::separate
        $matrix = [
            [-1, 0, -1],
            [0, 4, 0],
            [-1, 0, -1],
        ];

        $kernel = \ImagickKernel::fromMatrix($matrix);
        $kernel->scale(4, \Imagick::NORMALIZE_KERNEL_VALUE);
        $diamondKernel = \ImagickKernel::fromBuiltIn(
            \Imagick::KERNEL_DIAMOND,
            "2"
        );

        $kernel->addKernel($diamondKernel);

        $kernelList = $kernel->separate();

        $output = '';
        $count = 0;
        foreach ($kernelList as $kernel) {
            $output .= "<br/>Kernel $count<br/>";
            $output .= Display::renderKernelTable($kernel->getMatrix());
            $count++;
        }

        return $output;
//Example end
    }
}
