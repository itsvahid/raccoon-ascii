<?php

declare(strict_types=1);

function convertVideoToImages(): void {
    exec('ffmpeg -i raccoon.mp4 -t 30 -vf "fps=15, format=gray, scale=240:60" images/output%d.png');
}

function imageToAscii(string $imagePath): string {
    $image = imagecreatefrompng($imagePath);

    $chars = [' ', '.', '-', '~', ':', '=', '+', '*', '#', '%', '@'];
    $width = imagesx($image);
    $height = imagesy($image);

    $asciiArt = '';
    for ($y = 5; $y < $height - 5; $y++) {
        for ($x = 40; $x < $width - 50; $x++) {
            $colorIndex = imagecolorat($image, $x, $y);
            $grayColor = imagecolorsforindex($image, $colorIndex)['red'];
            $asciiArt .= $chars[floor($grayColor * 10 / 255)];
        }
        $asciiArt .= "\n";
    }
    imagedestroy($image);
    return $asciiArt;
}

convertVideoToImages();

for ($i = 1; $i <= 450; $i++) {
    system("clear && printf '\e[3J'");
    echo imageToAscii("images/output$i.png");
    usleep(49000);
}