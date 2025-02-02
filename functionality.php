<?php
function image($file) {
    // $image is initialized with the name of the uploaded file, converted to lowercase
    $image = strtolower($file['file']['name']);
    // Replace non-alphanumeric characters with hyphens in the image name
    $image = preg_replace('/[^a-z0-9.]+/', '-', $image);
    // Append the current timestamp to the trimmed image name
    $image = time() . '-' . trim($image, '-');

    // Define the upload path for the image
    $upload = 'upload/' . $image;

    // Get the temporary file path of the uploaded image
    $temp = $file['file']['tmp_name'];
    
    // Get the file extension of the uploaded image
    $imagefiletype = strtolower(pathinfo($upload, PATHINFO_EXTENSION));
    // Check if the file is an actual image
    $check = getimagesize($temp);
    $uploadOK = 1;

    // Validate if it is an actual image
    if ($check != false) {
        $uploadOK = 1; // It's an image
    } else {
        $uploadOK = 0; // Not an image
    }

    // Restrict file type to only accept jpg
    if ($imagefiletype == 'jpg') {
        $uploadOK = 0; // Not acceptable
     } //  } else {
    // //     $uploadOK = 1; // Acceptable
    // // }

    // Check file size (must be less than 5000 bytes)
    if (isset($file['file']) && $file['file']['size'] < 5000) {
        $uploadOK = 0; // File too small
    }

    // Attempt to move the uploaded file to the target directory
    if ($uploadOK == '0') {
        return false; // File upload failed
    } else {
        if (move_uploaded_file($temp, $upload)) {
            return $image; // File upload successful
        } else {
            return false; // File upload failed
        }
    }

}
?>