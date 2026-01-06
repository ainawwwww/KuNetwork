
text/x-generic subcat_from_cat.php ( PHP script text )
<?php 

include 'db.php';
    $sql = "SELECT products.*, category_assign_to_product.pro_id, category_assign_to_product.cat_id
FROM products
INNER JOIN category_assign_to_product ON products.id_p=category_assign_to_product.pro_id WHERE category_assign_to_product.cat_id = ".$_POST['selectedValue'];
    
  $result =mysqli_query($conn,$sql);

        while($data = mysqli_fetch_array($result))
        {?>
            <option value='<?=$data['id_p']?>'><?=$data['product_name']?></option>
       <?php }	
    ?>  
