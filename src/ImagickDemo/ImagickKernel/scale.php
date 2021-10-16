<?php

namespace ImagickDemo\ImagickKernel;

use ImagickDemo\Display;

class scale extends \ImagickDemo\Example
{

    public function renderTitle(): string
    {
        return "scale";
    }


    public function renderDescription()
    {
        return "Scales a kernel.
         
NORMALIZE_KERNEL_VALUE

NORMALIZE_KERNEL_PERCENT

NORMALIZE_KERNEL_CORRELATE
         
         Please read http://www.imagemagick.org/api/morphology.php#ScaleKernelInfo";

    }

    public function render(
        ?string $activeCategory,
        ?string $activeExample
    )
    {
//Example ImagickKernel::scale
        $output = "";

        $matrix = [
            [-1, 0, -1],
            [0, 4, 0],
            [-1, 0, -1],
        ];

        $kernel = \ImagickKernel::fromMatrix($matrix);
        $kernelClone = clone $kernel;

        $output .= "Start kernel<br/>";
        $output .= Display::renderKernelTable($kernel->getMatrix());


        $output .= "Scaling with NORMALIZE_KERNEL_VALUE. The  <br/>";
        $kernel->scale(2, \Imagick::NORMALIZE_KERNEL_VALUE);
        $output .= Display::renderKernelTable($kernel->getMatrix());


        $kernel = clone $kernelClone;
        $output .= "Scaling by percent<br/>";
        $kernel->scale(2, \Imagick::NORMALIZE_KERNEL_PERCENT);
        $output .= Display::renderKernelTable($kernel->getMatrix());


        $matrix2 = [
            [-1, -1, 1],
            [-1, false, 1],
            [1, 1, 1],
        ];

        $kernel = \ImagickKernel::fromMatrix($matrix2);
        $output .= "Scaling by correlate<br/>";
        $kernel->scale(1, \Imagick::NORMALIZE_KERNEL_CORRELATE);
        $output .= Display::renderKernelTable($kernel->getMatrix());


        return $output;
//Example end
    }
}
