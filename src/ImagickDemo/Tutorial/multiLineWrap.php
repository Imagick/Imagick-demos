<?php


namespace ImagickDemo\Tutorial;

class WordWrap
{
    public $lineHeight;

    public $lines;

    function __construct($lineHeight, $lines)
    {
        $this->lineHeight = $lineHeight;
        $this->lines = $lines;
    }

    public function renderTitle(): string
    {
        return "Word wrapping";
    }

    public static function fromText(\Imagick $imagick, \ImagickDraw $draw, $text, $maxWidth)
    {
        $lineHeight = 0;
    
        //Check to see if we can add another word to this line
        $words = explode(" ", $text);

        $lines = array();
        $currentLine = '';
        foreach ($words as $word) {
            if (strlen($currentLine) > 0) {
                $possibleLine = $currentLine . ' '. $word;
            } else {
                $possibleLine = $word;
            }
            //If adding the next word would make the current line
            //too long, place that line into the array
            //and use the next word to start a new line.
            $metrics = $imagick->queryFontMetrics($draw, $possibleLine);
            if ($metrics['textWidth'] >= $maxWidth) {
                $lines[] = $currentLine;
                $currentLine = $word;
            }
            else {
                $currentLine = $possibleLine;
            }
            //Finally, update line height
            if ($metrics['textHeight'] > $lineHeight) {
                $lineHeight = $metrics['textHeight'];
            }
            
        }
        
        if (strlen($currentLine) > 0) {
            $lines[] = $currentLine;
        }

        //Put the lines in the right order
        $orderedLines = array_reverse($lines);

        return new self($lineHeight, $orderedLines);
    }
}


class multiLineWrap extends \ImagickDemo\Example
{
    public function renderTitle(): string
    {
        return "Tutorial multi-line wrap.";
    }

    /**
     * @param string|null $activeCategory
     * @param string|null $activeExample
     * @return string
     */
    public function render(
        ?string $activeCategory,
        ?string $activeExample
    ) {
        return "<img src='/customImage/$activeCategory/$activeExample' alt='example image' />";
    }
    
    public function renderDescription()
    {
        return "Hello, I am Galstaff.";
    }

    public function renderCustomImage()
    {
        $imagick = new \Imagick();

        
        $imagick->newPseudoImage(640, 480, 'xc:grey');
        $imagick->setImageFormat('png');
        $draw = new \ImagickDraw();
        $darkColor = new \ImagickPixel('brown');
        $lightColor = new \ImagickPixel('LightCoral');


        $draw->setStrokeColor($darkColor);
        $draw->setFillColor($lightColor);

        $draw->setStrokeWidth(2);
        $draw->setFontSize(36);


        
        $draw->setFont(realpath(__DIR__."/../../../fonts/Arial.ttf"));
        //$draw->annotation(50, 50, "Lorem Ipsum!");

        $text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.";

        $wordWrap = WordWrap::fromText(
            $imagick, 
            $draw,
            $text, 
            640
        );

        $xpos = 0;
        $ypos = 400;
        $i = 0;
        

        
        foreach ($wordWrap->lines as $line) {
            $imagick->annotateImage($draw, $xpos, $ypos - ($i * $wordWrap->lineHeight), 0, $line);
            $i++;
        }

        header("Content-Type: image/png");
        echo $imagick->getImageBlob();
    }
}
