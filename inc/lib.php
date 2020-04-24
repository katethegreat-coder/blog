<?php
 /**
* Universal function to check forms
*
* @param array $superglobale variables $_GET & $_POST
* @param array $fields tables to be checked
* @return bool
*/
function verifForm($superglobale, $fields) {
   // loop fields
   foreach ($fields as $field) {
       // check if the field exists and is not empty
       if(isset($superglobale[$field]) && !empty ($superglobale [$field])){
       $response=true;
   } else {
       return false;
   }
}
return $response;
}  
/**
 * Cut the picture into a square
 *
 * @param int $size size of the square
 * @param string $name picture's file name
 */
function thumb($size , $name) {

    // separate name from extension
    $beginName= pathinfo($name, PATHINFO_FILENAME);
    $extension= pathinfo($name, PATHINFO_EXTENSION);

    // define the entire name
    $entireName= __DIR__ . '/../uploads/' . $name;

    // create a thumbnail
    $thumbData=getimagesize($entireName);
    $finalWidth=$size;
    $finalHeight=$size;
    $thumbnailDestination= imagecreatetruecolor($finalWidth, $finalHeight);

    switch($thumbData['mime']) {
        case 'image/jpeg':
            $srcPicture= imagecreatefromjpeg($entireName);
            break;
        case 'image/png':
            $srcPicture= imagecreatefrompng($entireName);
            break;
        case 'image/gif':
            $srcPicture= imagecreatefromgif($entireName);
            break;
    }

    // set up distances from the corner and if the picture is a square
    $cornerDisY=0;
    $cornerDisX=0;

    if ($thumbData[0]>$thumbData[1]) {
        // distance from the corner if landscape
        $cornerDisX=($thumbData[0]-$thumbData[1])/2;
        $squareSizeSrc= $thumbData[1];
    }

    if ($thumbData[0]<=$thumbData[1]) {
        // distance from the corner if portrait
        $cornerDisY=($thumbData[1]-$thumbData[0])/2;
        $squareSizeSrc= $thumbData[0];
        }

    imagecopyresampled(
        $thumbnailDestination,
        $srcPicture,
        0,
        0,
        $cornerDisX,
        $cornerDisY,
        $finalWidth,
        $finalHeight,
        $squareSizeSrc,
        $squareSizeSrc,
    );

    // save the resampled picture and define the path
    $nameresampledThumb= __DIR__ . '/../uploads/' . $beginName . '-'. $size. 'x' . $size . '.' . $extension;

    switch ($thumbData['mime']) {
        case 'image/jpeg':
            imagejpeg($thumbnailDestination, $nameresampledThumb);
            break;
        case 'image/png':
            imagepng($thumbnailDestination, $nameresampledThumb);
            break;
        case 'image/gif':
            imagegif($thumbnailDestination, $nameresampledThumb);
            break;
    }
    
    imagedestroy($thumbnailDestination);
    imagedestroy($srcPicture);

}

/**
 * Resize the picture -75%
 * 
 * @param string $name picture's file name
 * @param int $percent perecent of the decrease
 *
 */
function resizedPicture ($name, $percent) {

    // separate name from extension
    $beginName= pathinfo($name, PATHINFO_FILENAME);
    $extension= pathinfo($name, PATHINFO_EXTENSION);

    // create the entire name of the picture
    $entireName= __DIR__ . '/../uploads/' . $name;

    // get picture's information
    $pictureData=getimagesize($entireName);

    // set size of the final picture
    $finalWidth= $pictureData[0] * $percent/100;  // [0]: width
    $finalHeight= $pictureData[1] * $percent/100; // [1]: height

    // create an empty space for the final picture in RAM Memory
    $pictureDestination= imagecreatetruecolor($finalWidth, $finalHeight);

// load source picture into memory depending on its type
switch($pictureData['mime']) {
    case 'image/jpeg':
        $srcPicture= imagecreatefromjpeg($entireName);
        break;
    case 'image/png':
        $srcPicture= imagecreatefrompng($entireName);
        break;
    case 'image/gif':
        $srcPicture= imagecreatefromgif($entireName);
        break;
}

// copy the source picture into the destination picture
imagecopyresampled(
    $pictureDestination,        
    $srcPicture,                
    0,                          
    0,                          
    0,                          
    0,                         
    $finalWidth,                
    $finalHeight,               
    $pictureData[0],            
    $pictureData[1]            
);

$nameresampledImage= __DIR__ . '/../uploads/' . $beginName . '-'. $percent.'.' . $extension;

switch ($pictureData['mime']) {
    case 'image/jpeg':
    imagejpeg($pictureDestination, $nameresampledImage);
        break;
    case 'image/png':
    imagepng($pictureDestination, $nameresampledImage);
        break;
    case 'image/gif':
    imagegif($pictureDestination, $nameresampledImage);
        break;
}

// destroy pictures not used to free any memory associated with pictures
imagedestroy($pictureDestination);
imagedestroy($srcPicture);

}