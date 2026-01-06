<?php
include '../db.php';
session_start();

if(isset($_POST['update_cat'])){

    if($_FILES['images']['name']=='')
    {
        $updatequery_woImg="UPDATE `categories_level1` SET `cat_name`='".$_POST['category']."',`product_filter_sidebar_status`='".$_POST['sidebar_status']."',`menu_status`='".$_POST['menu_status']."',`navbar_description`='".$_POST['cat_description']."' WHERE `cat_id`=".$_POST['lastid'];
        mysqli_query($conn,$updatequery_woImg);   
                            echo "<script>
                            alert('Category Has Been Updated Succesfully');
                            window.location='../categorylevelone.php';
                            </script>"; 
        //echo $updatequery_woImg;
    }
    else{
        //image upload start
        $allowedFormats = ['jpg', 'jpeg', 'png', 'gif']; // Allowed formats
        $maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes
        $maxWidth = 1920; // Maximum width
        $maxHeight = 1080; // Maximum height
    
        
        $cat_name=$_POST['category'];
        $menu_status=$_POST['menu_status'];
        $sidebar_status=$_POST['sidebar_status'];
        $cat_description=$_POST['cat_description'];
        $fileName = $_FILES['images']['name'];

                $fileTmpName = $_FILES['images']['tmp_name'];
                $fileSize = $_FILES['images']['size'];
                $fileType = $_FILES['images']['type'];
                $fileError = $_FILES['images']['error'];
                $uploadStatus=1;
    
                // Validate for upload errors
                if ($fileError !== UPLOAD_ERR_OK) {
                    echo "Error uploading file: $fileName. Error code: $fileError<br>";
    $uploadStatus=0;                    
                    
                }
    
                // Validate file size
                if ($fileSize > $maxFileSize) {
                    echo "File too large: $fileName exceeds 5 MB.<br>";
                    $uploadStatus=0; 
                   
                }
    
                // Validate file format
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if (!in_array($fileExtension, $allowedFormats)) {
                    
                    echo "Invalid file format: $fileName is not an allowed image type.<br>";
                    $uploadStatus=0;                     
                  
                }
    
                // Validate image dimensions
                list($width, $height) = getimagesize($fileTmpName);
                if ($width > $maxWidth || $height > $maxHeight) {
                    echo "Image dimensions too large: $fileName ($width x $height). Max allowed: 1920 x 1080.<br>";
    $uploadStatus=0; 
                    
                }
    
                // Generate a unique name and move the file
                $newFileName = uniqid('img_', true) . '.' . $fileExtension;
                $uploadDir = '../images/categories';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true); // Create directory if not exists
                }
    
                
    
                
                
            }
    
    
           if($uploadStatus==1 )
           {
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        // Generate a unique name and move the file
                        $newFileName = uniqid('img_', true) . '.' . $fileExtension;
                        $uploadDir = '../images/categories/';
                        $uploadPath = $uploadDir . $newFileName;
                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            //echo "File uploaded successfully: $fileName as $newFileName<br>";
                            if($_POST['existing_image']!=''){
                                if (file_exists('../images/categories/'.$_POST['existing_image'])) {
                                    unlink('../images/categories/'.$_POST['existing_image']);
                                }
                            }
                                
                            $upload_query="UPDATE `categories_level1` SET `cat_name`='$cat_name',`image`='$newFileName',`product_filter_sidebar_status`='$sidebar_status',`menu_status`='$menu_status',`navbar_description`='$cat_description' WHERE `cat_id`=".$_POST['lastid'];
                            mysqli_query($conn,$upload_query);   
                            echo "<script>
                            alert('Category Has Been Updated Succesfully');
                            window.location='../categorylevelone.php';
                            </script>"; 
                        }
                        
                        else
                        {
                            echo "Error moving file: $fileName<br>";
                            
                        }
            
                
  
    }

         }
                    
    
                        
    
                
                
   


?>