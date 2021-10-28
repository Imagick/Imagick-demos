<?php

declare(strict_types = 1);

namespace ImagickDemo\Imagick\Controls;


use ImagickDemo\Params\Channel;
use ImagickDemo\Params\ImagickColorParam;
use ImagickDemo\ToArray;
use Params\Create\CreateFromVarMap;
use Params\InputParameterList;
use Params\InputParameterListFromAttributes;
use Params\SafeAccess;
use ImagickDemo\Params\UnitRange;
use ImagickDemo\Params\Inverse;

class OpaquePaintImageControl implements InputParameterList
{
    use SafeAccess;
    use CreateFromVarMap;
    use ToArray;
    use InputParameterListFromAttributes;

    public function __construct(
        #[UnitRange(0.1, 'fuzz')]
        private string $fuzz,
        #[ImagickColorParam('rgb(58, 192, 255)', 'target_color')]
        private string $target_color,
        #[ImagickColorParam('rgb(128, 0, 0)', 'replacement_color')]
        private string $replacement_color,
        #[Inverse('inverse')]
        private string $inverse,
    ) {
    }

    public function getValuesForForm(): array
    {
        return [
            'target_color' => $this->target_color,
            'replacement_color' => $this->replacement_color,
            'fuzz' => $this->fuzz,
            'inverse' => getOptionFromOptions($this->inverse, getInverseOptions()),
        ];
    }
}