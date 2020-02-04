<?php
$imagedata = $_POST["imagedata"];
$filename = $_POST["filename"];
$imagedata = base64_decode($imagedata);
$image = imagecreatefromstring($imagedata);
header("Content-Type: image/png");
header("Cache-control: no-cache");
header("Content-Disposition: attachment; filename=". $filename);
imagepng($image);
imagedestroy($image);
?>
