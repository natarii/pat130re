<?php
    $numwords = 75;
    $numbits = 8;
    $data = [];
    $pixels_per_mm = 10;
    $cardwidth = 79.5;
    $wordpitch = 3;
    $markx = 5.5;
    $marky = 1;
    $holewidth = 1.8;
    $firstholecenter = 11;
    $betweenholes = 6.85;
    $startblank = 30;

    //sync
    for ($i=0;$i<$numwords;$i++) {
        $data[$i][7] = true;
    }

    //end marker
    $data[$numwords-1][6] = true;
    $data[$numwords-1][5] = true;
    $data[$numwords-1][4] = true;
    $data[$numwords-1][3] = true;
    
    //card type
    $data[0][6] = true;
    $data[0][1] = true;

    //pgm
    $data[1][5] = true; //chime
    $data[1][0] = true; //ch1

    //dow
    $data[3][1] = true;
    $data[3][2] = true;
    $data[3][3] = true;
    $data[3][4] = true;
    $data[3][5] = true;

    $data[4][0] = true; //pg1

    $data[6][0] = true; //am
    $data[6][2] = true; //1:
    $data[8][2] = true; //2
    $data[9][3] = true; //3

    $data[11][0] = true; //mode 1

    $im = imagecreatetruecolor($cardwidth * $pixels_per_mm, ($startblank + (3 * $numwords)) * $pixels_per_mm);
    $bottommost = imagesy($im)-1;
    $rightmost = imagesx($im)-1;
    imagefill($im, 0, 0, imagecolorallocate($im, 255, 255, 255));
    imageline($im, 0, 0, 0, $bottommost, imagecolorallocate($im, 127, 127, 127));
    imageline($im, $rightmost, 0, $rightmost, $bottommost, imagecolorallocate($im, 127, 127, 127));
    for ($i=0;$i<$numbits;$i++) {
        $x = $firstholecenter + ($betweenholes*$i);
        $x *= $pixels_per_mm;
    }

    for ($word=0;$word<$numwords;$word++) {
        for ($bit=0;$bit<$numbits;$bit++) {
            $x = $firstholecenter + ($betweenholes*(7-$bit));
            $x *= $pixels_per_mm;
            $y = $startblank + ($wordpitch * $word);
            $y *= $pixels_per_mm;
            if (isset($data[$word][$bit]) && $data[$word][$bit]) {
                imagefilledellipse($im, $x, $y, $markx*$pixels_per_mm, $marky*$pixels_per_mm, imagecolorallocate($im, 0, 0, 0));
            }
        }
    }

    imagepng($im, "out.png");