<?php

$input = imagecreatefrompng("wunderdog.png");
$output = imagecreatefrompng("wunderdog.png");

$width = imagesx($input);
$heigth = imagesy($input);

function color_value($r, $g, $b)
{
    return ($r << 16) + ($g << 8) + $b;
}

/*
Rules:

1. Start drawing upwards when the pixel color is 7, 84, 19.
2. Start drawing left when the pixel color is 139, 57, 137.
3. Stop drawing when the pixel color is 51, 69, 169.
4. Turn right when the pixel color is 182, 149, 72.
5. Turn left when the pixel color is 123, 131, 154.
*/
$rules = array(1 => color_value(7, 84, 19),
               2 => color_value(139, 57, 137),
               3 => color_value(51, 69, 169),
               4 => color_value(182, 149, 72),
               5 => color_value(123, 131, 154)
);

function start_draw(&$input, &$output, $x, $y, $rule = 1)
{
    global $rules;
    /* default rule 1 */
    $xdir = 0;
    $ydir = -1;
    if ($rule == $rules[2]) {
        $xdir = -1;
        $ydir = 0;
    }

    $stop = false;
    while (!$stop) {
        if ($x < 0 || $y < 0 || $x >= imagesx($input) || $y >= imagesy($input)) {
            $stop = true;
            break;
        }
        imagesetpixel($output, $x, $y, 0);

        /* stop and turn rules */
        $rgb = imagecolorat($input, $x, $y);
        switch ($rgb) {
            case $rules[3]:
                $stop = true;
                break;
            case $rules[4]:
                $newxdir = -$ydir;
                $newydir = $xdir;
                $xdir = $newxdir;
                $ydir = $newydir;
                break;
            case $rules[5]:
                $newxdir = $ydir;
                $newydir = -$xdir;
                $xdir = $newxdir;
                $ydir = $newydir;
                break;
        }

        $x += $xdir;
        $y += $ydir;
    }
}

for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $heigth; $y++) {
        $rgb = imagecolorat($input, $x, $y);

        /* find start points */
        switch ($rgb) {
            case $rules[1]:
                start_draw($input, $output, $x, $y, $rules[1]);
                break;
            case $rules[2]:
                start_draw($input, $output, $x, $y, $rules[2]);
                break;
        }
    }
}

imagepng($output, "output.png");
imagedestroy($output);
