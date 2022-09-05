<?php

$upload = new fileUploadClass();
$upload->fileUpload();

class fileUploadClass {

    public function fileUpload() {
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'ppt'); // valid extensions
        $path = $_SERVER["DOCUMENT_ROOT"] . '/syswebfe_danny_mante/registro_equipo/'; // upload directory

        if ($_FILES['image']) {
            $img = $_FILES['image']['name'];
            $tmp = $_FILES['image']['tmp_name'];

// get uploaded file's extension
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

// can upload same image using rand function
            $final_image = rand(1000, 1000000) . $img;
            //echo 'FINAL IMAGE: ' . $final_image;
            //echo '<br>';
// check's valid format
            if (in_array($ext, $valid_extensions)) {
                $path = $path . $img;

                if (move_uploaded_file($tmp, $path)) {
                  //echo "<img src='$path' />";
                  echo $path;
                  } 
            } else {
                echo 'invalid';
            }
        }
    }

}
