<?php
include '../db.php';
session_start();

if(isset($_POST['insert'])){

    $p_name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $p_price = mysqli_real_escape_string($conn, $_POST['p_price']);
    $p_discount_price = mysqli_real_escape_string($conn, $_POST['p_discount_price']);
    $p_badge = mysqli_real_escape_string($conn, $_POST['p_badge']);
    $p_description = mysqli_real_escape_string($conn, $_POST['Pro_description']);
    $Pro_detailed_description = mysqli_real_escape_string($conn, $_POST['Pro_detailed_description']);
    $p_stock = mysqli_real_escape_string($conn, $_POST['p_stock']);
    $total_category=$_POST['total_category'];

    
    
    



    //image upload start
    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif']; // Allowed formats
    $maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes
    $maxWidth = 1920; // Maximum width
    $maxHeight = 1080; // Maximum height
    $uploadStatusarr = array();
    $num=0;
    

    // Check if files were uploaded
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $fileName) {
            $fileTmpName = $_FILES['images']['tmp_name'][$key];
            $fileSize = $_FILES['images']['size'][$key];
            $fileType = $_FILES['images']['type'][$key];
            $fileError = $_FILES['images']['error'][$key];
            $uploadStatus=1;
            $num++;

            // Validate for upload errors
            if ($fileError !== UPLOAD_ERR_OK) {
                echo "Error on Image ".$num."<br>";
                echo "Error uploading file: $fileName. Error code: $fileError<br>";
                array_push($uploadStatusarr, 0);
                continue;
                
                
            }

            // Validate file size
            if ($fileSize > $maxFileSize) {
                echo "Error on Image ".$num."<br>";
                echo "File too large: $fileName exceeds 5 MB.<br>";
                array_push($uploadStatusarr, 0);
                continue;
            }

            // Validate file format
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedFormats)) {
                echo "Error on Image ".$num."<br>";
                echo "Invalid file format: $fileName is not an allowed image type.<br>";
                array_push($uploadStatusarr, 0);
                continue;
            }

            // Validate image dimensions
            list($width, $height) = getimagesize($fileTmpName);
            if ($width > $maxWidth || $height > $maxHeight) {
                echo "Error on Image ".$num."<br>";
                echo "Image dimensions too large: $fileName ($width x $height). Max allowed: 1920 x 1080.<br>";
                array_push($uploadStatusarr, 0);
                continue;
            }

            // Generate a unique name and move the file
            $newFileName = uniqid('img_', true) . '.' . $fileExtension;
            $uploadDir = '../images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create directory if not exists
            }

            
                array_push($uploadStatusarr, 1);

            
            
        }


        if(!in_array( 0, $uploadStatusarr ))
        {
            $insert_query="INSERT INTO `products`(`product_name`, `product_price`, `discount_price`, `product_discription`, `product_detail_description`, `Badge`, `stock`) VALUES ('$p_name','$p_price','$p_discount_price','$p_description','$Pro_detailed_description','$p_badge','$p_stock')";
            //echo $insert_query;

            if(mysqli_query($conn,$insert_query)) {
                $last_id = $conn->insert_id;
                for($catnum=1;$catnum<=$total_category;$catnum++){
                    
                    $cat1=$_POST['cat1_'.$catnum];
                    $cat2=$_POST['cat2_'.$catnum];
                    $cat3=$_POST['cat3_'.$catnum];
                    if($cat3==0){
                        $level=2;
                        $category=$cat2;
                    }
                    else{
                        $level=3;
                        $category=$cat3;
                    }

                    $cat_insert_query = "INSERT INTO `category_assign_to_product`(`pro_id`, `cat_id`,`level`) VALUES ('$last_id','$category','$level')";
                //echo $cat_insert_query;
                    mysqli_query($conn,$cat_insert_query);

                }
                

                foreach ($_FILES['images']['name'] as $key => $fileName) {
                    $fileTmpName = $_FILES['images']['tmp_name'][$key];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    // Generate a unique name and move the file
                    $newFileName = uniqid('img_', true) . '.' . $fileExtension;
                    $uploadDir = '../images/';
                    $uploadPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmpName, $uploadPath)) {
                        //echo "File uploaded successfully: $fileName as $newFileName<br>";
                        $upload_query="INSERT INTO `product_images`( `product_id`, `image`) VALUES ('$last_id','$newFileName')";
                        mysqli_query($conn,$upload_query);    
                    }
                    else
                    {
                        echo "Error moving file: $fileName<br>";
                    }
        
                }
                echo "<script>
                        alert('Product Has Been Uploaded Succesfully');
                        window.location='../products.php';
                        </script>";
            }

            
            
            }
            


        }
        
        
        
    }
    // image upload end

   
      
  



?>