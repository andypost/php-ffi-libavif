<?php

$libavif = \FFI::load('./includes/libavif.h');

//var_dump($libavif->AVIF_PLANES_YUV);
///var_dump($libavif->AVIF_PLANES_ALL);

echo 'Version: ' . $libavif->avifVersion() . PHP_EOL;
echo 'LibYUVVersion: ' . $libavif->avifLibYUVVersion() . PHP_EOL;

$s = str_repeat(' ', 256);
$libavif->avifCodecVersions($s);
echo 'Codec versions: ' . $s . PHP_EOL;

function checkError($r) {
  global $libavif;
  if ($r !== $libavif->AVIF_RESULT_OK) {
    var_dump($libavif->avifResultToString($r));
    exit(1);
  }
}

$decoder = $libavif->avifDecoderCreate();

/*
$image = $libavif->avifImageCreateEmpty();
$r = $libavif->avifDecoderReadFile($decoder, $image, $file);
var_dump($libavif->avifResultToString($r));

var_dump($image->width, $image->height, $image->depth, $decoder->imageCount);
*/

foreach (new DirectoryIterator('./images') as $fileInfo) {
  if ($fileInfo->isDot()) continue;
  $file = $fileInfo->getFilename();

  $r = $libavif->avifDecoderSetIOFile($decoder, './images/' . $file);
  checkError($r);
  $r = $libavif->avifDecoderParse($decoder);
  checkError($r);

  printf('File %s has %d images and %d duration' . PHP_EOL, $file, $decoder->imageCount, $decoder->duration);
  $count = 0;
  do {
    $r = $libavif->avifDecoderNextImage($decoder);
    if ($r === $libavif->AVIF_RESULT_NO_IMAGES_REMAINING) {
      break;
    }
    elseif ($r !== $libavif->AVIF_RESULT_OK) {
      var_dump($libavif->avifResultToString($r));
      break;
    }
    $image = $decoder->image;
    printf('-#%d info %d x %d x %d' . PHP_EOL, $count++, $image->width, $image->height, $image->depth);
  } while (TRUE);

}
