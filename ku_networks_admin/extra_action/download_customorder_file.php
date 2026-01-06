<?php
if(isset($_POST['download']))
{

    $file = basename($_POST['file']);
$file = '../custom_order_files/'.$file;

if(!file_exists($file)){ // file does not exist
    //die('file not found');
    echo "<script>alert('File not Found!!');
    window.location.href='../order_history.php'</script>";
} else {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$file");
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");

    // read the file from disk
    readfile($file);
}
    
    
}
?>