
<?php 

include '../db.php';
    $sql = "SELECT * FROM `categories_level2` WHERE `parent_catid`=".$_POST['selectedValue'];
    
  $result =mysqli_query($conn,$sql);

        while($data = mysqli_fetch_array($result))
        {?>
            <option value='<?=$data['id']?>'><?=$data['name']?></option>
       <?php }	
    ?>  
