<?php

include 'db.php';
include 'check_login.php';


if (isset($_GET['id']) && isset($_POST['submit'])) {
    $id = $_GET['id'];  
    $level_id = $_POST['level_id']; 
    $min_bonus = $_POST['min_bonus']; 
    $max_bonus = $_POST['max_bonus'];  


    $query = "UPDATE level_upgrade_bonus SET level_id = ?, min_bonus = ?, max_bonus = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("idii", $level_id, $min_bonus, $max_bonus, $id);


    if ($stmt->execute()) {
        header("Location: level_bonus.php?success=Level Upgrade Requirement Updated Successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Invalid request. Please try again.</div>";
}
?>
