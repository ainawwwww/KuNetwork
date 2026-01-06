<?php


if (!isset($_SESSION))
    {
        session_start();
    }
if (!isset($_SESSION['A_id'])) {

    header("location:pages/examples/login.php");

  exit();

}
?>