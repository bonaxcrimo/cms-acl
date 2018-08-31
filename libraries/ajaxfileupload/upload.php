<?php
 
if ($_FILES)
{
    $tmp = $_FILES['file_input']['tmp_name'];
    $type = $_FILES['file_input']['type'];
    $size = $_FILES['file_input']['size'];
    $filename = $_FILES['file_input']['name'];
    $path = pathinfo($_SERVER['PHP_SELF']);
    $destination = $path['dirname'] . '/' . $filename;
    if (move_uploaded_file($tmp, $_SERVER['DOCUMENT_ROOT'] . $destination))
        $status = 1;
    else
        $status = 2;
 
    $hasil = array(
        'status' => $status,
        'filename' => $filename,
        'type' => $type,
        'size' => $size,
    );
    echo json_encode($hasil);
}
?>