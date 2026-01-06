<?php
include 'db.php';
include 'check_login.php';

if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = $_POST['id'];  


    $query = "DELETE FROM level_upgrade_bonus WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);  


    if ($stmt->execute()) {
        header("Location: level_bonus.php?success=Bonus Deleted Successfully");
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();  
}
?>
