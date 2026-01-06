<?php
include '../db.php';
session_start();

if(isset($_POST['update_cat'])){

    
        $updatequery_woImg="UPDATE `categories_level3` SET `name`='".$_POST['category']."',`product_filter_sidebar_status`='".$_POST['sidebar_status']."',`menu_status`='".$_POST['menu_status']."' WHERE `id`=".$_POST['lastid'];
        mysqli_query($conn,$updatequery_woImg);   
                            echo "<script>
                            alert('Category Has Been Updated Succesfully');
                            window.location='../categorylevelthree.php';
                            </script>"; 
        //echo $updatequery_woImg;
    
                
  
    }

         
                    
    
                        
    
                
                
   


?>