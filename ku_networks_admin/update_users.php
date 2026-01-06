<?php
include 'db.php';
include 'check_login.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country_id = $_POST['country_id'];
   
    $referral_code = $_POST['referral_code'];
    $status = isset($_POST['status']) ? 1 : 0;


    $result = mysqli_query($conn, "SELECT image FROM users WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);
    $image_name = $row['image']; 

 
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $image_name);
    }


    $query = "
        UPDATE users 
        SET name = '$name', user_id = '$user_id', email = '$email', phone = '$phone', 
            country_id = '$country_id', referral_code = '$referral_code', 
            status = '$status', image = '$image_name'
        WHERE id = '$id'
    ";

    if (mysqli_query($conn, $query)) {
        header("Location: users.php?success=User updated successfully");
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div class='alert alert-danger'>User ID is missing.</div>";
}
?>