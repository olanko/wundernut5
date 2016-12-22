<?php

$input = imagecreatefrompng("wunderdog.png");
$output = imagecreatefrompng("wunderdog.png");

$string = "test";
$orange = imagecolorallocate($output, 220, 210, 60);

$width = imagesx($input);
$heigth = imagesy($input);

function color_value($r, $g, $b) {
    return ($r << 16) + ($g << 8) + $b;
}

/*
Rules:

Start drawing upwards when the pixel color is 7, 84, 19.
Start drawing left when the pixel color is 139, 57, 137.
Stop drawing when the pixel color is 51, 69, 169.
Turn right when the pixel color is 182, 149, 72.
Turn left when the pixel color is 123, 131, 154.
*/

echo $width . ", " . $heigth;

for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $heigth; $y++) {
        $rgb = imagecolorat($input, $x, $y);


        if ($rgb == color_value(7, 84, 19)) {
            echo "$x, $y\n";
        }
    }
}

$px     = (imagesx($output) - 7.5 * strlen($string)) / 2;
imagestring($output, 3, $px, 9, $string, $orange);
imagepng($output, "output.png");
imagedestroy($output);
