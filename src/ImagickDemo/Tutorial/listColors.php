<?php


namespace ImagickDemo\Tutorial;

//http://www.imagemagick.org/script/color.php

class listColors extends \ImagickDemo\Example
{
    public function renderTitle(): string
    {
        return "Color list";
    }

    public function renderDescription()
    {
        return "Any of the named colors listed here can be passed to Imagick wherever a 
        color (ImagickPixel) argument is expected.";
    }
    
    public function render(
        ?string $activeCategory,
        ?string $activeExample
    )
    {
        $output = "<table class='smallPadding' width='100%'>";
        $output .= "<tbody>";
        
        foreach ($this->listOfColors as $color) {
            $pixelColor = new \ImagickPixel($color);
            $colorArray = $pixelColor->getColor();

            if ($color == 'transparent' || $color == 'none') {
                $rgbaString =  sprintf(
                    "rgb(%d, %d, %d, %d)",
                    $colorArray['r'],
                    $colorArray['g'],
                    $colorArray['b'],
                    $colorArray['a']
                );
                $hexString = sprintf(
                    "#%02x%02x%02x%02x",
                    $colorArray['r'],
                    $colorArray['g'],
                    $colorArray['b'],
                    $colorArray['a']
                );
            }
            else {
                $rgbaString =  sprintf(
                    "rgb(%d, %d, %d)",
                    $colorArray['r'],
                    $colorArray['g'],
                    $colorArray['b']
                );

                $hexString = sprintf(
                    "#%02x%02x%02x",
                    $colorArray['r'],
                    $colorArray['g'],
                    $colorArray['b']
                );
            }
            
            $colorString = '';
            if ($colorArray['r'] + $colorArray['g'] + $colorArray['b'] < 200) {
                if ($colorArray['a'] != 0) {
                    $colorString = 'color: #efefef';
                }
            }

            $output .= "<tr>";
                $output .= "<td>";
                $output .= $color;
                $output .= "</td>";
            
                $output .= "<td style='background-color: $hexString;$colorString'>";
                $output .= $color;
                $output .= "</td>";
            
                $output .= "<td>";
                $output .= $rgbaString;
                $output .= "</td>";

                $output .= "<td>";
                $output .= $hexString;
                $output .= "</td>";

            $output .= "</tr>";
        }
        
        
        $output .= "</tbody>";
        $output .= "</table>";
        return $output;
    }


    private $listOfColors = [

        "none", "black", "red", "magenta", "green", "cyan", "blue", "yellow",
        "white", "AliceBlue", "AntiqueWhite", "AntiqueWhite1", "AntiqueWhite2",
        "AntiqueWhite3", "AntiqueWhite4", "aqua", "aquamarine", "aquamarine1",
        "aquamarine2", "aquamarine3", "aquamarine4", "azure", "azure1", "azure2",
        "azure3", "azure4", "beige", "bisque", "bisque1", "bisque2", "bisque3",
        "bisque4", "BlanchedAlmond", "blue1", "blue2", "blue3", "blue4", "BlueViolet",
        "brown", "brown1", "brown2", "brown3", "brown4", "burlywood", "burlywood1",
        "burlywood2", "burlywood3", "burlywood4", "CadetBlue", "CadetBlue1", "CadetBlue2",
        "CadetBlue3", "CadetBlue4", "chartreuse", "chartreuse1", "chartreuse2",
        "chartreuse3", "chartreuse4", "chocolate", "chocolate1", "chocolate2",
        "chocolate3", "chocolate4", "coral", "coral1", "coral2", "coral3", "coral4",
        "CornflowerBlue", "cornsilk", "cornsilk1", "cornsilk2", "cornsilk3", "cornsilk4",
        "crimson", "cyan1", "cyan2", "cyan3", "cyan4", "DarkBlue", "DarkCyan",
        "DarkGoldenrod", "DarkGoldenrod1", "DarkGoldenrod2", "DarkGoldenrod3",
        "DarkGoldenrod4", "DarkGray", "DarkGreen", "DarkGrey", "DarkKhaki",
        "DarkMagenta", "DarkOliveGreen", "DarkOliveGreen1", "DarkOliveGreen2",
        "DarkOliveGreen3", "DarkOliveGreen4", "DarkOrange", "DarkOrange1", "DarkOrange2",
        "DarkOrange3", "DarkOrange4", "DarkOrchid", "DarkOrchid1", "DarkOrchid2",
        "DarkOrchid3", "DarkOrchid4", "DarkRed", "DarkSalmon", "DarkSeaGreen",
        "DarkSeaGreen1", "DarkSeaGreen2", "DarkSeaGreen3", "DarkSeaGreen4", "DarkSlateBlue",
        "DarkSlateGray", "DarkSlateGray1", "DarkSlateGray2", "DarkSlateGray3",
        "DarkSlateGray4", "DarkSlateGrey", "DarkTurquoise", "DarkViolet", "DeepPink",
        "DeepPink1", "DeepPink2", "DeepPink3", "DeepPink4", "DeepSkyBlue", "DeepSkyBlue1",
        "DeepSkyBlue2", "DeepSkyBlue3", "DeepSkyBlue4", "DimGray", "DimGrey", "DodgerBlue",
        "DodgerBlue1", "DodgerBlue2", "DodgerBlue3", "DodgerBlue4", "firebrick", "firebrick1",
        "firebrick2", "firebrick3", "firebrick4", "FloralWhite", "ForestGreen", "fractal", "freeze",
        "fuchsia", "gainsboro", "GhostWhite", "gold", "gold1", "gold2", "gold3", "gold4", "goldenrod",
        "goldenrod1", "goldenrod2", "goldenrod3", "goldenrod4", "gray", "gray", "gray0", "gray1",
        "gray10", "gray100", "gray100", "gray11", "gray12", "gray13", "gray14", "gray15", "gray16",
        "gray17", "gray18", "gray19", "gray2", "gray20", "gray21", "gray22", "gray23", "gray24",
        "gray25", "gray26", "gray27", "gray28", "gray29", "gray3", "gray30", "gray31", "gray32",
        "gray33", "gray34", "gray35", "gray36", "gray37", "gray38", "gray39", "gray4", "gray40",
        "gray41", "gray42", "gray43", "gray44", "gray45", "gray46", "gray47", "gray48", "gray49",
        "gray5", "gray50", "gray51", "gray52", "gray53", "gray54", "gray55", "gray56", "gray57",
        "gray58", "gray59", "gray6", "gray60", "gray61", "gray62", "gray63", "gray64", "gray65",
        "gray66", "gray67", "gray68", "gray69", "gray7", "gray70", "gray71", "gray72", "gray73",
        "gray74", "gray75", "gray76", "gray77", "gray78", "gray79", "gray8", "gray80", "gray81",
        "gray82", "gray83", "gray84", "gray85", "gray86", "gray87", "gray88", "gray89", "gray9",
        "gray90", "gray91", "gray92", "gray93", "gray94", "gray95", "gray96", "gray97", "gray98",
        "gray99", "green", "green1", "green2", "green3", "green4", "GreenYellow", "grey", "grey0",
        "grey1", "grey10", "grey100", "grey11", "grey12", "grey13", "grey14", "grey15", "grey16",
        "grey17", "grey18", "grey19", "grey2", "grey20", "grey21", "grey22", "grey23", "grey24",
        "grey25", "grey26", "grey27", "grey28", "grey29", "grey3", "grey30", "grey31", "grey32",
        "grey33", "grey34", "grey35", "grey36", "grey37", "grey38", "grey39", "grey4", "grey40",
        "grey41", "grey42", "grey43", "grey44", "grey45", "grey46", "grey47", "grey48", "grey49",
        "grey5", "grey50", "grey51", "grey52", "grey53", "grey54", "grey55", "grey56", "grey57",
        "grey58", "grey59", "grey6", "grey60", "grey61", "grey62", "grey63", "grey64", "grey65",
        "grey66", "grey67", "grey68", "grey69", "grey7", "grey70", "grey71", "grey72", "grey73",
        "grey74", "grey75", "grey76", "grey77", "grey78", "grey79", "grey8", "grey80", "grey81",
        "grey82", "grey83", "grey84", "grey85", "grey86", "grey87", "grey88", "grey89", "grey9",
        "grey90", "grey91", "grey92", "grey93", "grey94", "grey95", "grey96", "grey97", "grey98",
        "grey99", "honeydew", "honeydew1", "honeydew2", "honeydew3", "honeydew4", "HotPink",
        "HotPink1", "HotPink2", "HotPink3", "HotPink4", "IndianRed", "IndianRed1", "IndianRed2",
        "IndianRed3", "IndianRed4", "indigo", "ivory", "ivory1", "ivory2", "ivory3", "ivory4",
        "khaki", "khaki1", "khaki2", "khaki3", "khaki4", "lavender", "LavenderBlush", "LavenderBlush1",
        "LavenderBlush2", "LavenderBlush3", "LavenderBlush4", "LawnGreen", "LemonChiffon",
        "LemonChiffon1", "LemonChiffon2", "LemonChiffon3", "LemonChiffon4", "LightBlue", "LightBlue1",
        "LightBlue2", "LightBlue3", "LightBlue4", "LightCoral", "LightCyan", "LightCyan1", "LightCyan2",
        "LightCyan3", "LightCyan4", "LightGoldenrod", "LightGoldenrod1", "LightGoldenrod2",
        "LightGoldenrod3", "LightGoldenrod4", "LightGoldenrodYellow", "LightGray", "LightGreen",
        "LightGrey", "LightPink", "LightPink1", "LightPink2", "LightPink3", "LightPink4",
        "LightSalmon", "LightSalmon1", "LightSalmon2", "LightSalmon3", "LightSalmon4", "LightSeaGreen",
        "LightSkyBlue", "LightSkyBlue1", "LightSkyBlue2", "LightSkyBlue3", "LightSkyBlue4",
        "LightSlateBlue", "LightSlateGray", "LightSlateGrey", "LightSteelBlue", "LightSteelBlue1",
        "LightSteelBlue2", "LightSteelBlue3", "LightSteelBlue4", "LightYellow", "LightYellow1",
        "LightYellow2", "LightYellow3", "LightYellow4", "lime", "LimeGreen", "linen", "magenta1",
        "magenta2", "magenta3", "magenta4", "maroon", "maroon", "maroon1", "maroon2", "maroon3",
        "maroon4", "MediumAquamarine", "MediumBlue", "MediumForestGreen", "MediumGoldenRod",
        "MediumOrchid", "MediumOrchid1", "MediumOrchid2", "MediumOrchid3", "MediumOrchid4",
        "MediumPurple", "MediumPurple1", "MediumPurple2", "MediumPurple3", "MediumPurple4",
        "MediumSeaGreen", "MediumSlateBlue", "MediumSpringGreen", "MediumTurquoise",
        "MediumVioletRed", "MidnightBlue", "MintCream", "MistyRose", "MistyRose1", "MistyRose2",
        "MistyRose3", "MistyRose4", "moccasin", "NavajoWhite", "NavajoWhite1", "NavajoWhite2",
        "NavajoWhite3", "NavajoWhite4", "navy", "NavyBlue", "matte", "OldLace", "olive", "OliveDrab",
        "OliveDrab1", "OliveDrab2", "OliveDrab3", "OliveDrab4", "opaque", "orange", "orange1",
        "orange2", "orange3", "orange4", "OrangeRed", "OrangeRed1", "OrangeRed2", "OrangeRed3",
        "OrangeRed4", "orchid", "orchid1", "orchid2", "orchid3", "orchid4", "PaleGoldenrod",
        "PaleGreen", "PaleGreen1", "PaleGreen2", "PaleGreen3", "PaleGreen4", "PaleTurquoise",
        "PaleTurquoise1", "PaleTurquoise2", "PaleTurquoise3", "PaleTurquoise4", "PaleVioletRed",
        "PaleVioletRed1", "PaleVioletRed2", "PaleVioletRed3", "PaleVioletRed4", "PapayaWhip",
        "PeachPuff", "PeachPuff1", "PeachPuff2", "PeachPuff3", "PeachPuff4", "peru", "pink",
        "pink1", "pink2", "pink3", "pink4", "plum", "plum1", "plum2", "plum3", "plum4", "PowderBlue",
        "purple", "purple", "purple1", "purple2", "purple3", "purple4", "red1", "red2", "red3",
        "red4", "RosyBrown", "RosyBrown1", "RosyBrown2", "RosyBrown3", "RosyBrown4", "RoyalBlue",
        "RoyalBlue1", "RoyalBlue2", "RoyalBlue3", "RoyalBlue4", "SaddleBrown", "salmon", "salmon1",
        "salmon2", "salmon3", "salmon4", "SandyBrown", "SeaGreen", "SeaGreen1", "SeaGreen2",
        "SeaGreen3", "SeaGreen4", "seashell", "seashell1", "seashell2", "seashell3", "seashell4",
        "sienna", "sienna1", "sienna2", "sienna3", "sienna4", "silver", "SkyBlue", "SkyBlue1",
        "SkyBlue2", "SkyBlue3", "SkyBlue4", "SlateBlue", "SlateBlue1", "SlateBlue2", "SlateBlue3",
        "SlateBlue4", "SlateGray", "SlateGray1", "SlateGray2", "SlateGray3", "SlateGray4", "SlateGrey",
        "snow", "snow1", "snow2", "snow3", "snow4", "SpringGreen", "SpringGreen1", "SpringGreen2",
        "SpringGreen3", "SpringGreen4", "SteelBlue", "SteelBlue1", "SteelBlue2", "SteelBlue3",
        "SteelBlue4", "tan", "tan1", "tan2", "tan3", "tan4", "teal", "thistle", "thistle1", "thistle2",
        "thistle3", "thistle4", "tomato", "tomato1", "tomato2", "tomato3", "tomato4", "transparent",
        "turquoise", "turquoise1", "turquoise2", "turquoise3", "turquoise4", "violet", "VioletRed",
        "VioletRed1", "VioletRed2", "VioletRed3", "VioletRed4", "wheat", "wheat1", "wheat2",
        "wheat3", "wheat4", "WhiteSmoke", "yellow1", "yellow2", "yellow3", "yellow4", "YellowGreen",];
}
