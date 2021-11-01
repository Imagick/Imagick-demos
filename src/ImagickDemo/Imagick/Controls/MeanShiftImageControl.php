<?php

declare(strict_types = 1);

namespace ImagickDemo\Imagick\Controls;



use ImagickDemo\ToArray;
use Params\Create\CreateFromVarMap;
use Params\InputParameterListFromAttributes;
use Params\SafeAccess;

use ImagickDemo\Params\Channel;
use ImagickDemo\Params\Image;
use ImagickDemo\Params\Width;
use ImagickDemo\Params\Height;
use ImagickDemo\Params\ZeroOrAboveFloat;


class MeanShiftImageControl
{
    use SafeAccess;
    use CreateFromVarMap;
    use ToArray;
    use InputParameterListFromAttributes;

    public function __construct(
        #[Width(10, 'width')]
        private float $width,
        #[Height(10, 'height')]
        private float $height,
        // TODO - we should have a root 3 quantum scale param...
        #[ZeroOrAboveFloat(0.15, 100, 'color_distance')]
        private float $color_distance,
        #[Image('image_path')]
        private string $image_path,
    ) {
    }

    public function getValuesForForm(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
            'color_distance' => $this->color_distance,
            'image_path' => getOptionFromOptions($this->image_path, getImagePathOptions()),
        ];
    }

    public function getImagePath(): string
    {
        return $this->image_path;
    }
}