<?php
// Convert + compress a folder of images to WebP.
// Usage: php convert.php <srcDir> <destDir> [maxEdge] [quality]

[$script, $src, $dest] = array_pad($argv, 3, null);
$maxEdge = (int) ($argv[3] ?? 2400);
$quality = (int) ($argv[4] ?? 80);

if (! $src || ! $dest || ! is_dir($src)) {
    fwrite(STDERR, "bad args\n");
    exit(1);
}

@mkdir($dest, 0775, true);

$exts = ['jpg', 'jpeg', 'png', 'heic', 'heif', 'webp'];
$files = scandir($src);
sort($files);

$seen = [];      // md5 of source pixels -> output name (dedupe)
$srcBytes = 0;
$outBytes = 0;
$count = 0;
$skipped = 0;

foreach ($files as $f) {
    $path = "$src/$f";
    if (! is_file($path)) {
        continue;
    }
    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
    if (! in_array($ext, $exts, true)) {
        continue;
    }

    $base = pathinfo($f, PATHINFO_FILENAME);
    // normalise "name(1)" -> "name-2"
    $base = preg_replace_callback('/\((\d+)\)$/', fn ($m) => '-' . ($m[1] + 1), $base);
    $base = strtolower(preg_replace('/[^A-Za-z0-9_-]+/', '-', $base));
    $out = "$dest/$base.webp";

    try {
        $img = new Imagick($path);
        $img->setFirstIterator();

        // dedupe on decoded signature
        $sig = $img->getImageSignature();
        if (isset($seen[$sig])) {
            $skipped++;
            $img->clear();
            continue;
        }
        $seen[$sig] = $out;

        // Manual EXIF auto-orient (autoOrientImage() missing in this Imagick build).
        $white = new ImagickPixel('#ffffff');
        switch ($img->getImageOrientation()) {
            case Imagick::ORIENTATION_TOPRIGHT:    $img->flopImage(); break;
            case Imagick::ORIENTATION_BOTTOMRIGHT: $img->rotateImage($white, 180); break;
            case Imagick::ORIENTATION_BOTTOMLEFT:  $img->flopImage(); $img->rotateImage($white, 180); break;
            case Imagick::ORIENTATION_LEFTTOP:     $img->flopImage(); $img->rotateImage($white, 90); break;
            case Imagick::ORIENTATION_RIGHTTOP:    $img->rotateImage($white, 90); break;
            case Imagick::ORIENTATION_RIGHTBOTTOM: $img->flopImage(); $img->rotateImage($white, 270); break;
            case Imagick::ORIENTATION_LEFTBOTTOM:  $img->rotateImage($white, 270); break;
        }
        $img->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

        $w = $img->getImageWidth();
        $h = $img->getImageHeight();
        if (max($w, $h) > $maxEdge) {
            if ($w >= $h) {
                $img->resizeImage($maxEdge, 0, Imagick::FILTER_LANCZOS, 1);
            } else {
                $img->resizeImage(0, $maxEdge, Imagick::FILTER_LANCZOS, 1);
            }
        }

        $img->stripImage();
        $img->setImageFormat('webp');
        $img->setOption('webp:method', '6');
        $img->setImageCompressionQuality($quality);
        $img->writeImage($out);
        $img->clear();

        $srcBytes += filesize($path);
        $outBytes += filesize($out);
        $count++;
    } catch (Throwable $e) {
        fwrite(STDERR, "FAIL $f: {$e->getMessage()}\n");
    }
}

printf(
    "%s -> %s\n  %d converted, %d dupes skipped\n  %.1f MB -> %.1f MB (%.0f%% smaller)\n",
    $src, $dest, $count, $skipped,
    $srcBytes / 1048576, $outBytes / 1048576,
    $srcBytes ? (1 - $outBytes / $srcBytes) * 100 : 0
);
