<?php

    function uploadFile($fileObject) {
        $imageDirectory = getcwd() . '/../images/';
        $fullImageDir = $imageDirectory . 'full/';
        $resizeImageDir = $imageDirectory . 'resize/';

        $newFileName = time() . '_' . basename($fileObject["name"]);
        $uploadFile = $fullImageDir . $newFileName;
        $resizeFile = $resizeImageDir . $newFileName;

        if(!move_uploaded_file($fileObject['tmp_name'], $uploadFile)) {
            exit('Error uploading image.');
        }

        $image = new Imagick($uploadFile);
        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
        $image->setImageCompressionQuality(95);
        $image->resizeImage(1024, 768, Imagick::FILTER_CATROM, 1, true);
        $image->writeImage($resizeFile);

        return $newFileName;
    }
?>
