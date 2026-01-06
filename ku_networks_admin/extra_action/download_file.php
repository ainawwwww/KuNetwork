<?php
if(isset($_POST['download']))
{

    $file = basename($_POST['file']);
$file = '../../inquiry_files/'.$file;

if(!file_exists($file)){ // file does not exist
    //die('file not found');
    echo "<script>alert('File not Found!!');
    window.location.href='../inquiries.php'</script>";
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